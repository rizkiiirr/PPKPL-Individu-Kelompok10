# US 1.2 — Input Lokasi Titik Uji Tanah Berbasis GPS

**User Story:** Sebagai Surveyor, saya ingin menginput koordinat GPS titik bor tanah agar lokasi pengujian dapat terdefinisi dengan akurat dan terdokumentasi ke sistem.

• **Context File:**
  - `app/Models/SoilTestModel.php` — Main model for Soil Testing
  - `app/Models/SoilLocation.php` — Model for GPS coordinates with relationships
  - `app/Actions/StoreSoilLocationAction.php` — Business logic for coordinate processing
  - `app/Http/Controllers/SoilLocationController.php` — Thin controller for location flow
  - `app/Http/Requests/StoreLocationRequest.php` — Validation rules for GPS ranges
  - `resources/views/locations/create.blade.php` — Input form with Leaflet.js
  - `resources/views/locations/show.blade.php` — Detail view with map marker

• **Skills:**
  - `skills/skill.md` — Laravel 12 patterns (Actions, Thin Controllers, Form Requests, Precision Decimals)

• **Task:** Implement the full GPS coordinate input flow for US 1.2:
  1. Officer enters the Soil Test detail page.
  2. Officer clicks "Add Location Coordinate".
  3. System runs StoreSoilLocationAction::execute(SoilTest, $data):
     - Validates latitude and longitude types.
     - Checks if Soil Test ID exists.
     - Stores coordinates with DECIMAL(11,8) precision to avoid rounding errors.
  4. System redirects to locations.show with a success toast notification.
  5. The Map View renders a marker at the exact GPS coordinate.

• **Input:**
  - @param SoilTest $soilTest — The parent soil test record (Route Model Binding).
  - @param StoreLocationRequest $request — Authenticated request with validated coordinates.

• **Output:**
  - @return RedirectResponse — Redirect to location detail on success.
  - @return SoilLocation — Persisted Eloquent model.
  - @return View — Rendered Leaflet.js map view.
  - //@return Boolean true — Location successfully linked to Soil Test.

• **Rules:**
  - [R1] Range Guard — Latitude must be -90 to 90; Longitude -180 to 180.
  - [R2] Auth Guard — Only 'Surveyor' role can access the store route.
  - [R3] Accuracy Guard — Coordinate fields must use decimal in migration to prevent float point inaccuracies.
  - [R4] Atomicity — Location creation must be wrapped in DB::transaction() if linked with multiple metadata.

• **What Changed:**
  - **NEW** `app/Actions/StoreSoilLocationAction.php` — Business logic: coordinate mapping and relational storage.
  - **NEW** `app/Http/Controllers/SoilLocationController.php` — Thin controller: index, create, store, show.
  - **NEW** `app/Http/Requests/StoreLocationRequest.php` — Validation: required, numeric, coordinate range.
  - **NEW** `app/Models/SoilLocation.php` — Location model with soil_test() relationship.
  - **MOD** `app/Models/SoilTest.php` — Added hasOne(SoilLocation::class) relationship.
  - **NEW** `database/migrations/2026_04_12_000001_create_soil_locations_table.php` — Migration with decimal(11,8).
  - **NEW** `resources/views/locations/create.blade.php` — Form with Leaflet map integration.
  - **NEW** `resources/views/locations/show.blade.php` — Map detail view (Tailwind + Leaflet).
  - **MOD** `routes/web.php` — Added auth-guarded resource routes for location.

• **Commit Message:** feat(location): implement US 1.2 GPS coordinate input with Leaflet integration
  - Add StoreSoilLocationAction for coordinate processing
  - Add SoilLocation model with decimal precision (11,8)
  - Implement StoreLocationRequest for GPS range validation
  - Add Blade views with Leaflet.js for map rendering
  - Register resource routes in web.php
