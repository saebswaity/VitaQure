## System Overview (User‑Focused)

### What this system does
- Manage patients, lab orders (called "groups"), tests and cultures, pricing, doctors, accounting, branches, contracts, and expenses.
  - Generate, sign, print, and share reports as PDFs.
- Provide an embedded AI helper to guide staff.

### Pages and what they contain

- Home
  - Public welcome/landing page with a brief introduction and links to sign in or navigate to key areas.

- Admin Login and Logout
  - Secure staff sign‑in with email/username + password, remember‑me, and password recovery. Logout ends the session and returns to login.

- Admin Home and Dashboard
  - Landing and operational overview with key performance indicators, quick links, recent activity summaries, and shortcuts into common workflows (create group, search patients, view reports).

- Reports
  - End‑to‑end reporting workspace. Contains a filterable table of patient orders (groups), a details view with selected tests/cultures, result entry areas, status indicators, and actions to generate PDF reports, sign them, and email recipients.

- Groups (Lab Orders)
  - Order management for lab work. Includes index/search, create/edit screens to select the patient, add tests and cultures, view financial breakdown (subtotal, discount, total, paid, due), generate/print barcodes, and track completion status for each item.

- Patients
  - Patient registry. A searchable list with pagination, a form to add/edit patient demographics and contacts, bulk import from template, and export for reporting or migration.

- Doctors
  - Directory of referring physicians. Includes CRUD pages to store contact details and commission settings, plus import/export to maintain the list efficiently.

- Tests
  - Master catalog of lab tests. Screens to define test metadata, components, units, reference ranges, categories, and special flags (for example, separated tests).

- Cultures
  - Catalog of culture tests. Includes forms to define culture types and link relevant result fields and antibiotics to support sensitivity testing.

- Culture Options
  - Structured vocabulary used in culture results (for example, organism names, specimen/source, stain results). Provides lists and forms to create/edit standardized options for consistent reporting.

- Antibiotics
  - Reference list of antibiotics for sensitivity testing. Includes index and forms to add/edit drugs used when entering culture results.

- Prices
  - Pricing console. Dedicated pages to view and update test and culture prices, with spreadsheet‑style inputs, validation, and bulk import/export to streamline updates.

- Accounting
  - Financial reporting area. Staff can filter by date, branch, and doctor to generate revenue and commission reports, then view, print, or download summaries for reconciliation.

- Chat
  - Internal messaging module for staff collaboration. Provides a conversation list and message thread view for quick coordination within the team.

- Visits
  - Visit tracking for patients. Pages to log visits, attach tests, review visit details, and monitor progress.

- Branches
  - Branch directory. Maintain locations with address, contact info, and operational notes to support reporting and routing.

- Contracts
  - Contract management. Define partner accounts, discount schemes, terms, and effective dates used during order pricing.

- Expenses & Categories
  - Expense ledger and classification list. Create/review expense entries and manage categories that organize the ledger and drive reporting.

- Backups
  - Backup management. View available backups, create new backups, download archives, and restore when permitted.

- Activity Logs
  - Audit center. A searchable table shows who did what and when (user, action, timestamp, IP/device). Includes tools to clear older logs when necessary.

- Settings
  - Configurations console. Organize organization profile, email templates, report styles (fonts, sizes, header/footer imagery), SMS/WhatsApp templates and toggles, and administrative API‑key inputs.

- Roles & Users
  - Role‑based access control center and user administration. Create roles and toggle fine‑grained permissions (view/create/edit/delete/sign per module), add/edit staff accounts, assign roles, reset passwords, and manage status.

- Translations
  - Translation workspace. Search and edit UI strings to localize the system and support RTL languages where applicable.

- Vita AI Chatbot and Chat UI
  - Full‑page and floating chat assistants to answer questions. Includes message history, input box, send/clear actions, and a settings area to pick the AI model, set initial context, and control conversation saving.

- Patient Portal
  - Auth: Patients can register or sign in, request a quick‑login code by email, and use code‑based login.
  - Dashboard: Personal overview with recent orders, available reports/receipts, and links to profile and library.
  - Groups: Order history with access to view/download reports (PDF) and receipts, and regenerate PDFs when allowed.
  - Profile: Account settings to update name, contact details, and password/preferences.
  - Visits: Read‑only or limited view of visit dates and linked orders (as permitted).
  - Branches: Directory with location and contact details to find nearby service points.
  - Tests Library: Educational library of available analyses and culture tests to understand offerings and preparation requirements (where provided).

### Quick help
- Can’t print a report? Ensure at least one test/culture is selected, then try again. If it still fails, contact an admin.
- Chatbot not responding? Refresh the page or clear the conversation. If issues persist, try again later.

### Pages & Routes (Detailed)

#### Home
- Route: /zaid
- Description: Public welcome/landing page with a brief introduction and links to sign in or navigate to key areas.

#### Admin Login
- Route: /
- Description: Secure sign‑in page for staff. Contains email/username and password fields, remember‑me option, CSRF protection, validation errors, and links for password recovery. Redirects to the admin dashboard upon successful login.

#### Admin Logout
- Route: POST /admin/logout
- Description: Ends the current admin session and redirects back to the login screen to keep the account secure.

#### Admin Password Reset
- Route: /admin/reset/*
- Description: Complete password recovery workflow for admins. Includes a page to request a reset email, a token‑based page to set a new password, and submission/confirmation screens.

#### Admin Home
- Route: /admin
- Description: Admin landing page greeting the user and providing quick entry points to core modules (Reports, Groups, Patients, etc.).

#### Admin Dashboard
- Route: /admin/dashboard
- Description: Operational overview with key performance indicators, quick links, recent activity summaries, and shortcuts into common workflows (create group, search patients, view reports).

#### Admin VitaGuard
- Route: /admin/vitaguard, POST /admin/vitaguard/predict
- Description: AI‑assisted decision support. The page contains an input form (text and/or file inputs), a predict/submit button, and a results panel that displays model insights, confidence, and guidance for staff.

#### Admin Vita AI Chatbot
- Route: /admin/vitachatbot
- Description: Full‑page embedded assistant for staff. Includes a message history panel, input box, send/clear actions, and a settings drawer to pick the AI model, set initial context, and control conversation saving.

#### Admin Reports
- Route: /admin/reports, POST /admin/reports/pdf/{id}, POST /admin/reports/update_culture/{id}, GET /admin/get_reports, GET /admin/sign_report/{id}, POST /admin/reports/send_report_mail/{id}
- Description: End‑to‑end reporting workspace. Contains a filterable table of patient orders (groups), a details view with selected tests/cultures, result entry areas, status indicators, and actions to generate PDF reports, sign them, and email recipients.

#### Admin Groups
- Route: /admin/groups, POST /admin/groups/print_barcode/{group_id}, GET /admin/get_groups, GET /admin/visits/create_tests/{id}
- Description: Order management for lab work. Includes index/search, create/edit screens to select the patient, add tests and cultures, view financial breakdown (subtotal, discount, total, paid, due), generate/print barcodes, and track completion status for each item.

#### Admin Patients
- Route: /admin/patients, GET /admin/get_patients, GET /admin/patients_export, GET /admin/patients_download_template, POST /admin/patients_import
- Description: Patient registry. The pages provide a searchable list with pagination, a form to add/edit patient demographics and contacts, bulk import from template, and export for reporting or migration.

#### Admin Doctors
- Route: /admin/doctors, GET /admin/get_doctors, GET /admin/doctors_export, GET /admin/doctors_download_template, POST /admin/doctors_import
- Description: Directory of referring physicians. Includes CRUD pages to store contact details and commission settings, plus import/export to maintain the list efficiently.

#### Admin Tests
- Route: /admin/tests, GET /admin/get_tests
- Description: Master catalog of lab tests. Screens to define test metadata, components, units, reference ranges, categories, and special flags (e.g., separated tests).

#### Admin Cultures
- Route: /admin/cultures, GET /admin/get_cultures
- Description: Catalog of culture tests. Includes forms to define culture types and link relevant result fields and antibiotics to support sensitivity testing.

#### Admin Culture Options
- Route: /admin/culture_options, GET /admin/get_culture_options
- Description: Structured vocabulary used in culture results (e.g., organism names, specimen/source, stain results). Provides lists and forms to create/edit standardized options for consistent reporting.

#### Admin Antibiotics
- Route: /admin/antibiotics, GET /admin/get_antibiotics
- Description: Reference list of antibiotics for sensitivity testing. Includes index and forms to add/edit drugs used when entering culture results.

#### Admin Prices
- Route: GET/POST /admin/prices/tests, GET /admin/tests_prices_export, POST /admin/tests_prices_import, GET/POST /admin/prices/cultures, GET /admin/cultures_prices_export, POST /admin/cultures_prices_import
- Description: Pricing console. Dedicated pages to view and update test and culture prices, with spreadsheet‑style inputs, validation, and bulk import/export to streamline updates.

#### Admin Accounting
- Route: /admin/accounting, GET /admin/generate_report, GET /admin/doctor_report, GET /admin/generate_doctor_report
- Description: Financial reporting area. Staff can filter by date, branch, and doctor to generate revenue and commission reports, then view, print, or download summaries for reconciliation.

#### Admin Chat
- Route: /admin/chat
- Description: Internal messaging module for staff collaboration. Provides a conversation list and message thread view for quick coordination within the team.

#### Admin Visits
- Route: /admin/visits, GET /admin/get_visits
- Description: Visit tracking for patients. Pages to log visits, attach tests, review visit details, and monitor progress.

#### Admin Branches
- Route: /admin/branches, GET /admin/get_branches
- Description: Branch directory. Maintain locations with address, contact info, and operational notes to support reporting and routing.

#### Admin Contracts
- Route: /admin/contracts, GET /admin/get_contracts
- Description: Contract management. Define partner accounts, discount schemes, terms, and effective dates used during order pricing.

#### Admin Expenses
- Route: /admin/expenses, GET /admin/get_expenses
- Description: Expense ledger. Create and review expense entries with amount, date, vendor/notes, and associated category to support accounting.

#### Admin Expense Categories
- Route: /admin/expense_categories, GET /admin/get_expense_categories
- Description: Classification list for expenses. Add/edit categories that organize the expense ledger and drive categorization in reports.

#### Admin Backups
- Route: /admin/backups
- Description: Backup management. View available backups, create new backups, download archives, and restore when permitted.

#### Admin Activity Logs
- Route: /admin/activity_logs, POST /admin/activity_logs_clear, GET /admin/get_activity_logs
- Description: Audit center. A searchable table shows who did what and when (user, action, timestamp, IP/device). Includes tools to clear older logs when necessary.

#### Admin Settings
- Route: /admin/settings, POST /admin/settings/info, POST /admin/settings/emails, POST /admin/settings/reports, POST /admin/settings/sms, POST /admin/settings/whatsapp, POST /admin/settings/api_keys
- Description: Configurations console. Organize organization profile, email templates, report styles (fonts, sizes, header/footer imagery), SMS/WhatsApp templates and toggles, and administrative API‑key inputs.

#### Admin Roles
- Route: /admin/roles, GET /admin/get_roles
- Description: Role‑based access control center. Create roles and toggle fine‑grained permissions (view/create/edit/delete/sign per module) to control what users can do.

#### Admin Users
- Route: /admin/users, GET /admin/get_users
- Description: User administration. Add/edit staff accounts, assign roles, reset passwords, and manage status.

#### Admin Translations
- Route: /admin/translations
- Description: Translation workspace. Search and edit UI strings to localize the system and support RTL languages where applicable.

#### Admin Updates
- Route: /admin/update/{version}
- Description: Controlled update action. Initiates an update to a specific version under admin supervision.

#### Patient Auth
- Route: GET /patient/login/register, POST /patient/login/register_submit, GET /patient/login/login, POST /patient/login/login_submit, GET /patient/login/mail, POST /patient/login/mail_submit, GET /patient/login/patient/login/{code}, POST /logout
- Description: Patient portal access. Pages let a patient register, sign in, request a quick‑login code by email, use code‑based login, and sign out.

#### Patient Dashboard
- Route: /patient
- Description: Patient home. Displays a personal overview with recent orders, available reports/receipts, and links to profile and library.

#### Patient Groups
- Route: /patient/groups, GET /patient/groups/reports/{id}, GET /patient/groups/receipt/{id}, POST /patient/groups/reports/pdf/{id}, GET /patient/get_patient_groups
- Description: Patient order history. A list of the patient’s lab orders with access to view/download reports (PDF) and receipts, and regenerate PDFs when allowed.

#### Patient Profile
- Route: GET /patient/profile, POST /patient/profile
- Description: Account settings for patients. Edit name, contact details, and password/preferences to keep records up to date.

#### Patient Visits
- Route: /patient/visits
- Description: Read‑only or limited view of the patient’s visits depending on permissions, showing visit dates and linked orders.

#### Patient Branches
- Route: /patient/branches
- Description: Branch directory for patients with location and contact details to find nearby service points.

#### Patient Tests Library
- Route: /patient/tests_library, GET /patient/get_analyses, GET /patient/get_cultures
- Description: Educational library of available analyses and culture tests. Search/browse to understand offerings and preparation requirements (where provided).

#### AI Chat UI
- Route: /ai-chat/embed
- Description: Embedded chat window used across the system. Presents conversation history, an input box, send/clear controls, and access to settings to tailor responses.

#### AI Chat API
- Route: GET /ai-chat/api/models, POST /ai-chat/api/chat, POST /ai-chat/api/clear
- Description: Endpoints that power the chat UI: fetch available models, submit a message to receive an answer, and clear the session history.

#### Chatbot Proxy (Flask)
- Route: /chatbot_langchain/*
- Description: Reverse‑proxy mount that exposes an external Flask chatbot under the /chatbot_langchain path for seamless access from the web app.

#### Utilities
- Route: GET /change_locale/{lang}, GET /clear-cache, POST /posts/store, POST /test/store, DELETE /admin/catogery/{catogeryTest}
- Description: Various helper actions. Change the interface language, clear application cache for troubleshooting, and internal endpoints used to store posts/tests or remove a category item.

