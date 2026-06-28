---
name: laravel-livewire-soil-testing
description: 'Comprehensive guidelines for developing a Soil Bearing Capacity Testing System using Laravel 12, Livewire, Leaflet.js, and MinIO.'
---

# Laravel 12 & Livewire Development Standards for Soil Testing

Your goal is to assist the development team in building a highly reliable, reactive, and secure application for managing soil bearing capacity tests from scheduling to homeowner notification.

## 1. Core Architecture & Code Style
- **Pattern:** Follow the standard Laravel MVC architecture. Use Service classes (`App\Services`) to isolate complex business logic from Controllers and Livewire components [1].
- **Type Safety:** Enforce PHP 8.4+ strict typing for all properties, parameters, and return types.
- **Component Design:** Utilize Laravel Livewire with traditional Blade templates for reactive user interfaces, avoiding page reloads during form submissions and state updates.

## 2. Database Schema & Eloquent Models
Strictly map Eloquent models to the predefined exact table names. Disable default pluralization if necessary by declaring `protected $table = 'table_name';`.
- `User` maps to `Users`
- `Proyek` maps to `Proyek`
- `PengajuanUji` maps to `J1_Pengajuan_Uji_Tanah`
- `JadwalTitik` maps to `J1_Jadwal_Titik_Uji`
- `HasilSondir` maps to `J1_Hasil_Sondir`
- `Sertifikat` maps to `J1_Sertifikat_Tanah`

**Schema Constraints:**
- For `J1_Jadwal_Titik_Uji`, generate migrations forcing geospatial coordinate precision: `$table->decimal('latitude', 10, 8)` and `$table->decimal('longitude', 11, 8)` to prevent data truncation [2].

## 3. Reactive UI & Leaflet.js Integration
- **DOM Preservation:** Always wrap the Leaflet map container `<div>` with the `wire:ignore` directive. This prevents Livewire's DOM-diffing algorithm from re-rendering the element and destroying the map instance [3].
- **State Synchronization:** Bind Leaflet click events to update Livewire state using `@this.set('latitude', lat)` and `@this.set('longitude', lng)`.

## 4. Object Storage (MinIO) & File Handling
- **Storage Driver:** Configure and use the `s3` disk driver in `config/filesystems.php` to point to the MinIO instance [4].
- **Upload Workflow:** Utilize Livewire's `WithFileUploads` trait.
- **Pathing Convention:** Store files using the structure: `certificates/proyek_{proyek_id}/hasil_{hasil_id}_{timestamp}.{ext}`.
- **Security:** Do not expose permanent public URLs. Use `Storage::disk('s3')->temporaryUrl()` when rendering download links for the certificates.

## 5. Business Logic & Data Validation
- **Calculations:** The logic to determine `indikator_awal` from `nilai_qc` and `nilai_fs` must be executed within a dedicated Service class before saving to `J1_Hasil_Sondir`.
- **Validation:** Implement rigorous validation rules using Livewire's `$rules` property. Ensure GPS inputs are not empty and fall within valid geographical boundaries.

## 6. Configuration & Environment Management
- **Environment Variables:** Never hardcode credentials, API keys, or MinIO endpoints. 
- **Retrieval:** Always access environment variables via the `config()` helper function (referencing `config/services.php` or `config/filesystems.php`). Do not use the `env()` function directly within application logic to ensure compatibility with `php artisan config:cache`.

## 7. Transaction Management
- **Atomicity:** When performing operations that affect multiple related tables (e.g., creating `J1_Jadwal_Titik_Uji` and triggering subsequent records), wrap the queries within `DB::transaction()`. This guarantees atomic operations and automatic rollback upon failure [5].

## 8. Logging & Error Handling
- **Audit Trails:** Implement structured logging using the `Log` facade. Log critical state changes, such as: `Log::info('Certificate verified for Hasil ID: {id}')`.
- **Exception Masking:** Catch exceptions in the Service layer. Do not expose SQL or stack trace errors to the client. Return standardized, user-friendly error messages via Livewire state or session flash messages.

## 9. Asynchronous Processing
- **Event Delegation:** Notifications regarding foundation eligibility sent to the Homeowner (`Pemilik`) must not block the HTTP request cycle. 
- **Queues:** Delegate these notifications by implementing Laravel Jobs that use the `ShouldQueue` interface, processed by a background worker [6].

## 10. Testing & Quality Assurance
- **Testing Framework:** Use Pest PHP or PHPUnit.
- **Coverage:** Implement Feature tests for Livewire component rendering and end-to-end workflow completion. Implement Unit tests for data calculation logic (e.g., verifying `indikator_awal` outputs based on varying `qc` and `fs` inputs).
