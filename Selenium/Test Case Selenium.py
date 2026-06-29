import unittest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException

class TestSoilTestingSystem(unittest.TestCase):

    def setUp(self):
        # Inisialisasi WebDriver (Chrome)
        options = webdriver.ChromeOptions()
        options.add_argument('--headless') # Mode eksekusi tanpa antarmuka grafis
        options.add_argument('--no-sandbox')
        self.driver = webdriver.Chrome(options=options)
        self.driver.implicitly_wait(10)
        self.base_url = "http://127.0.0.1:8000"

    def login(self, email, password):
        """Fungsi pembantu untuk proses autentikasi."""
        self.driver.get(f"{self.base_url}/login")
        self.driver.find_element(By.ID, "email").send_keys(email)
        self.driver.find_element(By.ID, "password").send_keys(password)
        self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()

    def test_tc01_login_berhasil(self):
        """
        TC-01: Login Berhasil Setelah Perubahan Sistem
        Memverifikasi fungsionalitas autentikasi dasar pasca-implementasi CR.
        """
        self.login("kontraktor@example.com", "password")
        
        try:
            # Memastikan halaman mengalihkan ke dasbor
            WebDriverWait(self.driver, 5).until(
                EC.presence_of_element_located((By.ID, "dashboard-header"))
            )
            current_url = self.driver.current_url
            self.assertIn("/dashboard", current_url, "URL dasbor tidak ditemukan setelah login.")
        except TimeoutException:
            self.fail("Sistem gagal memproses login atau elemen dasbor tidak termuat.")

    def test_tc02_pengajuan_tanah_notifikasi_petugas(self):
        """
        TC-02: Kontraktor Mengajukan Pengujian Tanah, Notifikasi Terkirim
        Memverifikasi alur pengajuan dan kemunculan notifikasi pada sisi Petugas Lab.
        """
        # Skenario Positif: Kontraktor membuat pengajuan
        self.login("kontraktor@example.com", "password")
        self.driver.get(f"{self.base_url}/pengajuan/create")
        
        self.driver.find_element(By.ID, "nama_proyek").send_keys("Proyek Uji Fondasi A")
        self.driver.find_element(By.ID, "lokasi").send_keys("Banjarmasin")
        self.driver.find_element(By.CSS_SELECTOR, "button.btn-submit").click()
        
        # Logout Kontraktor
        self.driver.get(f"{self.base_url}/logout")
        
        # Verifikasi dari sisi Petugas Lab
        self.login("petugaslab@example.com", "password")
        self.driver.get(f"{self.base_url}/notifications")
        
        # Mengecek eksistensi komponen notifikasi baru
        try:
            notification_element = WebDriverWait(self.driver, 5).until(
                EC.presence_of_element_located((By.XPATH, "//div[contains(text(), 'Pengajuan baru: Proyek Uji Fondasi A')]"))
            )
            self.assertTrue(notification_element.is_displayed(), "Notifikasi tidak muncul di antarmuka Petugas Lab.")
        except TimeoutException:
            self.fail("Observer gagal mengirimkan notifikasi ke dasbor Petugas Lab.")

    def test_tc04_jadwal_lokasi_notifikasi_multicast(self):
        """
        TC-04: Petugas Lab Menjadwalkan Lokasi, Notifikasi Terkirim ke Teknisi dan Kontraktor
        Memverifikasi sistem pengiriman notifikasi multi-peran secara simultan.
        """
        # Petugas Lab menjadwalkan lokasi
        self.login("petugaslab@example.com", "password")
        self.driver.get(f"{self.base_url}/jadwal/1/edit") # Asumsi ID pengajuan = 1
        
        self.driver.find_element(By.ID, "tanggal_uji").send_keys("2026-07-15")
        self.driver.find_element(By.ID, "teknisi_id").send_keys("Teknisi A")
        self.driver.find_element(By.CSS_SELECTOR, "button.btn-update-status").click()
        self.driver.get(f"{self.base_url}/logout")
        
        # Verifikasi penerimaan notifikasi oleh Teknisi
        self.login("teknisi@example.com", "password")
        self.driver.get(f"{self.base_url}/notifications")
        try:
            teknisi_notif = WebDriverWait(self.driver, 5).until(
                EC.presence_of_element_located((By.XPATH, "//div[contains(text(), 'Jadwal baru ditugaskan')]"))
            )
            self.assertTrue(teknisi_notif.is_displayed())
        except TimeoutException:
            self.fail("Notifikasi jadwal tidak mencapai dasbor Teknisi.")
            
    def tearDown(self):
        # Terminasi sesi WebDriver setelah setiap pengujian
        self.driver.quit()

if __name__ == "__main__":
    # Menjalankan rangkaian pengujian regresi
    unittest.main(verbosity=2)