# US 1.5 — Notifikasi Kelayakan Fondasi

**User Story:**  
Sebagai Pemilik Rumah, saya ingin menerima notifikasi hasil kelayakan fondasi berdasarkan uji tanah agar saya dapat mengetahui tingkat keamanan konstruksi rumah saya sebelum dilanjutkan.

• **Context File:**
  - `app/Models/SoilTestModel.php` — Parent model for soil testing record
  - `app/Models/FoundationEligibility.php` — Model for final eligibility status
  - `app/Notifications/FoundationStatusNotification.php` — Laravel Notification class
  - `app/Http/Controllers/NotificationController.php` — Controller for managing alerts
  - `resources/views/notifications/index.blade.php` — Notification list for homeowners

• **Skills:**
  - `skills/skill.md` — Laravel 12 patterns (Notifications, Event Triggers, Thin Controllers)

• **Task:** Implement the foundation eligibility notification flow for US 1.5:
  1. System detects that the Certificate (US 1.4) has been successfully verified.
  2. Laboratory Officer triggers the "Send Notification" action.
  3. System runs `FoundationStatusNotification::send(Owner, Status)`:
     - Checks the final eligibility status (Layak/Tidak Layak).
     - Prepares notification content based on test data.
     - Sends an alert to the Homeowner's dashboard.
  4. System updates the `notification_sent_at` timestamp in the database.
  5. Homeowner receives a real-time notification about their construction safety.

• **Input:**
  - `@param SoilTest $soilTest` — The completed soil test record (Route Model Binding)
  - `@param User $owner` — The recipient of the notification (Homeowner)

• **Output:**
  - `@return RedirectResponse` — Success message after notification is queued
  - `@return Boolean true` — Notification successfully delivered to the database/mail

• **Rules:**
  - **[R1] Completion Guard** — Notifications can only be sent if the status is 'Terverifikasi' (US 1.4 complete)
  - **[R2] Auth Guard** — Access to trigger notifications is restricted to the 'Petugas Lab' role
  - **[R3] Visibility Guard** — Homeowners can only see notifications linked to their own project ID
  - **[R4] Data Integrity** — The notification must include a direct link to the verified certificate in MinIO

• **What Changed:**
  - **NEW** `app/Notifications/FoundationStatusNotification.php` — Notification logic for homeowners
  - **NEW** `app/Http/Controllers/NotificationController.php` — Thin controller for triggering alerts
  - **MOD** `app/Models/SoilTest.php` — Added method to check eligibility before notifying
  - **NEW** `resources/views/notifications/index.blade.php` — Homeowner dashboard for safety alerts
  - **MOD** `routes/web.php` — Added notification routes for laboratory and owners

• **Commit Message:** feat(notification): implement US 1.5 foundation eligibility alerts for homeowners
- Add FoundationStatusNotification for real-time safety updates
- Implement notification trigger logic in NotificationController
- Add status completion check before sending alerts
- Setup homeowner notification dashboard view
