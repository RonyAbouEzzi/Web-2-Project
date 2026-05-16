# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel 12 municipal e-services platform for Lebanon. Citizens submit service requests, make payments, and track progress. Office users manage requests and services. Admins oversee municipalities, offices, and users. Built as a university project (Web Programming 2, Antonine University) with a 5-person team using feature branches.

## Commands

### Development (all three terminals needed)

```bash
php artisan serve --host=127.0.0.1 --port=8000   # Backend
npm run dev                                        # Vite dev server
php artisan queue:work                             # Queue worker
```

Or use the combined dev command:

```bash
composer dev    # Runs server, queue, logs, and vite concurrently
```

### Database

```bash
php artisan migrate:fresh --seed    # Reset DB with demo data
php artisan storage:link            # Create public storage symlink
```

### Testing

```bash
php artisan test                            # Run all tests
php artisan test --filter=ExampleTest       # Run a single test class
php artisan test tests/Feature/ExampleTest.php  # Run a specific test file
```

Tests use SQLite in-memory (configured in phpunit.xml).

### Code Style

```bash
./vendor/bin/pint    # Laravel Pint (PSR-12 based formatter)
```

### Cache Clear (after .env changes)

```bash
php artisan optimize:clear
```

## Architecture

### Role-Based Multi-Portal System

Three user roles, each with a dedicated controller, route group, and view directory:

| Role | Controller | Routes prefix | Views |
|------|-----------|---------------|-------|
| `admin` | `Admin\AdminController` | `/admin` (`admin.*`) | `views/admin/` |
| `office_user` | `Office\OfficeController` | `/office` (`office.*`) | `views/office/` |
| `citizen` | `Citizen\CitizenController` | `/citizen` (`citizen.*`) | `views/citizen/` |

Auth is handled by `Auth\AuthController` (login, register, social OAuth, 2FA, password reset).

Access control: `RoleMiddleware` (aliased as `role:admin`, `role:citizen`, etc.) enforces role checks. Citizens must also pass `citizen.profile.complete` middleware before submitting requests or payments.

### UI Framework: Sneat Admin Template

The frontend uses the **Sneat Bootstrap Admin** theme, not plain Tailwind. Key layout structure:

- `views/layouts/app.blade.php` — main authenticated layout (wraps the Sneat contentNavbar layout)
- `views/sneat-layouts/` — Sneat master templates (`commonMaster`, `contentNavbarLayout`, `blankLayout`)
- `resources/assets/vendor/` — Sneat's SCSS, JS, and third-party libs (compiled by Vite)
- `resources/menu/verticalMenu.json` — sidebar menu definition (shared to all views via `MenuServiceProvider`)

Vite config (`vite.config.js`) globs all Sneat asset files from `resources/assets/`. The `@` alias resolves to `resources/`.

### Reusable Blade Components

Located in `views/components/`: `card`, `data-table`, `modal`, `stat-card`, `status-badge`, `page-header`, `form-input`, `ui-button`, `empty-state`, `alert-box`, `auth-shell`.

### Service Layer

Business logic extracted into `app/Services/`:
- `PaymentService` — Stripe checkout session creation and payment processing
- `PdfService` — DomPDF document generation (receipts, request documents)
- `QrCodeService` — QR code generation for request tracking

### Realtime & Notifications

- Broadcasting via Pusher (`laravel-echo` + `pusher-js` on frontend)
- Events in `app/Events/`: `MessageSent`, `MessagesRead`, `ServiceRequestStatusUpdated`, `NewRequestSubmitted`, etc.
- Notifications in `app/Notifications/`: email + database channels for status updates, appointment reminders, registration confirmation

### Key Integrations

- **Stripe** — payment processing (test keys in dev)
- **Google/GitHub OAuth** — via Laravel Socialite
- **Google Maps** — office location display
- **Twilio SMS** — appointment reminders (`SMS_DRIVER=log` for dev, `twilio` for prod)
- **DomPDF + QR codes** — PDF receipts and trackable request documents

### Seeded Demo Accounts

- Admin: `admin@eservices.gov` / `password`
- Office: `manager@beirut.gov.lb` / `password`
- Citizen: `citizen1@test.com` / `password`

## Branch Strategy

- `main` — stable/production
- `dev` — integration branch
- Feature branches: `feature/auth-realtime`, `feature/admin-panel`, `feature/office-panel`, `feature/citizen-portal`, `feature/chat-maps-ui`
- PRs go from feature branch → `dev`, then `dev` → `main` after validation
