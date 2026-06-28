# US 1.3 — Input Nilai Daya Dukung Tanah Hasil Sondir

**User Story:**  
Sebagai Teknisi Lapangan, saya ingin menginput nilai daya dukung tanah hasil uji sondir agar sistem dapat mengevaluasi kekuatan tanah sebagai dasar perancangan fondasi.

• **Context File:**
  - `app/Models/SoilTestModel.php` — Parent model for soil testing record
  - `app/Models/HasilSondirModel.php` — Model for sondir test data (QC and FS values)
  - `app/Actions/StoreSoilLocationAction.php` — Business logic for processing test results
  - `app/Http/Controllers/TeknisiSondirController.php` — Thin controller for technical results flow
  - `app/Http/Requests/StoreSoilResultRequest.php` — Validation rules for numerical soil data
  - `resources/views/results/create.blade.php` — Input form for technical soil parameters

• **Skills:**
  - `skills/skill.md` — Laravel 12 patterns (Actions, Thin Controllers, Form Requests, Precision Decimals)

• **Task:** Implement the full soil bearing capacity input flow for US 1.3:
  1. Field Technician enters the "Input Hasil Uji" page.
  2. Technician enters values for Cone Resistance (QC) and Friction Sleeve (FS).
  3. System runs `StoreSoilBearingCapacityAction::execute(SoilTest, $data)`:
     - Validates numerical inputs for QC and FS.
     - Checks relationship with valid SoilTest ID.
     - Stores data in J1_Hasil_Sondir table with high decimal precision.
  4. System calculates an initial soil strength indicator.
  5. System redirects to dashboard with a success notification.

• **Input:**
  - `@param SoilTest $soilTest` — The parent soil test record (Route Model Binding)
  - `@param StoreSoilResultRequest $request` — Validated request containing QC and FS values

• **Output:**
  - `@return RedirectResponse` — Redirect to result summary on success
  - `@return SoilResult` — Persisted model containing technical soil data

• **Rules:**
  - **[R1] Numerical Guard** — QC and FS values must be numeric and non-negative
  - **[R2] Auth Guard** — Only 'Teknisi Lapangan' role can access this input route
  - **[R3] Accuracy Guard** — Column types must use decimal in migration to ensure engineering calculation accuracy
  - **[R4] Completion Guard** — Input is only allowed if the location coordinate (US 1.2) has been previously defined
  
• **What Changed:**
  - **NEW** `app/Actions/StoreSoilBearingCapacityAction.php` — Business logic for soil data processing
  - **NEW** `app/Http/Controllers/SoilResultController.php` — Thin controller: create and store methods
  - **NEW** `app/Http/Requests/StoreSoilResultRequest.php` — Validation: required, numeric, min:0
  - **NEW** `app/Models/SoilResult.php` — Model with relationship to SoilTest
  - **NEW** `database/migrations/2026_04_18_000001_create_j1_hasil_sondir_table.php` — Migration with decimal fields
  - **NEW** `resources/views/results/create.blade.php` — Technical input form for field measurements
  - **MOD** `app/Models/SoilTest.php` — Added `hasOne(SoilResult::class)` relationship

• **Commit Message:** feat(soil-test): implement US 1.3 soil bearing capacity input for technicians
- Add StoreSoilBearingCapacityAction for technical data storage
- Implement StoreSoilResultRequest for numerical validation
- Add SoilResult model and migration with decimal precision
- Add blade view for test result entry
