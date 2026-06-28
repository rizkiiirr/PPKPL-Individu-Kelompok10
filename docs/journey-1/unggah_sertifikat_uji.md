# US 1.4 — Unggah Sertifikat Hasil Pengujian Tanah

**User Story:**  
Sebagai Petugas Lab, saya ingin mengunggah sertifikat hasil pengujian tanah agar hasil uji memiliki bukti resmi yang valid dan dapat diverifikasi oleh pihak terkait.

• **Context File:**
  - `app/Models/SoilTestModel.php` — Parent model for soil testing record
  - `app/Models/SoilCertificate.php` — Model for storing certificate metadata
  - `app/Actions/UploadSoilCertificateAction.php` — Business logic for MinIO S3 upload
  - `app/Http/Controllers/SoilCertificateController.php` — Thin controller for certificate flow
  - `app/Http/Requests/StoreCertificateRequest.php` — Validation rules for PDF files
  - `resources/views/certificates/upload.blade.php` — Upload form interface

• **Skills:**
  - `skills/skill.md` — Laravel 12 patterns (Actions, Thin Controllers, Object Storage, Atomic Transactions)

• **Task:** Implement the full certificate upload flow for US 1.4:
  1. Laboratory Officer selects a completed Soil Test record.
  2. Officer clicks "Upload Certificate" and selects a PDF file.
  3. System runs `UploadSoilCertificateAction::execute(SoilTest, $file)`:
     - Validates that the file is a valid PDF.
     - Uploads the file to the MinIO S3 Bucket under the `certificates/` directory.
     - Generates a unique path/URL for the stored object.
     - Updates the SoilTest status to 'Terverifikasi'.
  4. System redirects to the detail page with a success toast notification.

• **Input:**
  - `@param SoilTest $soilTest` — The parent soil test record (Route Model Binding)
  - `@param StoreCertificateRequest $request` — Validated request containing the `sertifikat_uji` file

• **Output:**
  - `@return RedirectResponse` — Redirect to soil test detail on success
  - `@return Boolean true` — File successfully persisted in MinIO and DB updated

• **Rules:**
  - **[R1] File Type Guard** — Only PDF files are allowed (`mimes:pdf`)
  - **[R2] Size Guard** — Maximum file size is limited to 2MB to optimize storage
  - **[R3] Storage Guard** — Files must be stored in MinIO Object Storage, not the local server filesystem
  - **[R4] Auth Guard** — Only 'Petugas Lab' role has permission to upload and verify certificates

• **What Changed:**
  - **NEW** `app/Actions/UploadSoilCertificateAction.php` — Logic for MinIO file streaming and record linking
  - **NEW** `app/Http/Controllers/SoilCertificateController.php` — Thin controller: upload and store methods
  - **NEW** `app/Http/Requests/StoreCertificateRequest.php` — Validation: file, mimes:pdf, max:2048
  - **MOD** `config/filesystems.php` — Configured S3 driver for MinIO connection
  - **NEW** `resources/views/certificates/upload.blade.php` — File upload form with progress indicator
  - **MOD** `app/Models/SoilTest.php` — Added file_path and status update logic

• **Commit Message:** feat(soil-test): implement US 1.4 certificate upload with MinIO integration
- Add UploadSoilCertificateAction for S3 object storage
- Implement StoreCertificateRequest for PDF validation
- Configure MinIO storage driver in filesystems.php
- Add blade view for certificate management
