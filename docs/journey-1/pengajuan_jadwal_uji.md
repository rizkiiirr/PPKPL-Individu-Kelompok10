# US 1.1 — Pengajuan Jadwal Pengujian Tanah

**User Story:**  
Sebagai Kontraktor, saya ingin mengajukan permintaan jadwal uji tanah dengan mengisi detail proyek agar proses pengujian tanah dapat dijadwalkan secara resmi dan terdokumentasi ke sistem.

• **Context File:**
  - `app/Models/ProyekModel.php` — Model Proyek as parent data
  - `app/Models/SoilTestModel.php` — Main model for Soil Test requests
  - `app/Http/Controllers/PengajuanController.php` — Controller handling the request flow
  - `resources/views/kontraktor/pengajuan/create.blade.php` — Form for submitting requests
  - `resources/views/kontraktor/pengajuan/index.blade.php` — History of soil test requests

• **Skills:**
  - `skills/skill.md` — Laravel 12 patterns (Thin Controllers, Eloquent ORM, Form Validation)

• **Task:** Implement the soil test scheduling flow for US 1.1:
  1. Contractor enters the "Tambah Pengajuan" page.
  2. Contractor selects a project from the dropdown and enters the type of testing.
  3. System runs the `store()` method in `PengajuanController`:
     - Validates that the `proyek_id` exists in the database.
     - Captures the `Auth::id()` as the `kontraktor_id`.
     - Sets the default status to 'Menunggu Penjadwalan Lab'.
  4. System saves the record to the `j1_pengajuan_uji_tanah` table.
  5. System redirects back to the index page with a success flash message.

• **Input:**
  - `@param Request $request` — Contains `proyek_id` and `jenis_pengujian`

• **Output:**
  - `@return RedirectResponse` — Redirect to `pengajuan.index` on success
  - `@return SoilTestModel` — Newly created record in the database

• **Rules:**
  - **[R1] Existence Guard** — The selected Project ID must exist in the `proyek` table
  - **[R2] Auth Guard** — The system automatically assigns the logged-in user's ID as the requester
  - **[R3] Status Guard** — Every new request is initialized with the status 'Menunggu Penjadwalan Lab' per the User Journey

• **What Changed:**
  - **NEW** `app/Http/Controllers/PengajuanController.php` — Handles listing, creating, and storing requests
  - **NEW** `app/Models/SoilTestModel.php` — Model linked to the soil testing table
  - **MOD** `app/Models/ProyekModel.php` — Definition of project relationships
  - **NEW** `resources/views/kontraktor/pengajuan/create.blade.php` — Blade template for the request form
  - **MOD** `routes/web.php` — Resource routes for PengajuanController

• **Commit Message:** feat(soil-test): implement US 1.1 soil test schedule request for contractors
- Create PengajuanController for managing soil test requests
- Implement SoilTestModel with project and user relationships
- Add validation for project selection and test type
- Setup initial status 'Menunggu Penjadwalan Lab'
