# E-Services Management Platform

Laravel 12 web platform for municipal e-services in Lebanon.  
Citizens can submit requests, make payments, and track progress while offices and admins manage operations through role-specific dashboards.

## Project Description

This project was built for Web Programming 2 at Antonine University.  
It digitizes municipal workflows with:

- Role-based portals (`admin`, `office_user`, `citizen`)
- Service request lifecycle management
- Appointment booking and reminders
- Secure authentication (email/password, social login, 2FA)
- Realtime notifications and messaging

## Team Members

| Member | Role | Branch |
| --- | --- | --- |
| Rony | Team Lead | `feature/auth-realtime` |
| Ahmad | Admin Module | `feature/admin-panel` |
| Hasouna | Office Module | `feature/office-panel` |
| Hussein | Citizen Module | `feature/citizen-portal` |
| Maged | Chat + Maps UI | `feature/chat-maps-ui` |

Core branches:

- `main`: stable/production-ready
- `dev`: integration branch

## Tech Stack

### Backend

- PHP 8.2+
- Laravel 12
- MySQL 8

### Frontend

- Blade templates
- Vite
- Tailwind CSS 4
- Axios

### Integrations

- Google OAuth + GitHub OAuth (Laravel Socialite)
- Stripe payments
- Pusher broadcasting (realtime events)
- Twilio SMS reminders
- Mailtrap SMTP (development mail testing)
- Google Maps API
- DomPDF + QR code generation

### Developer Tooling

- PHPUnit
- Laravel Pint
- Composer + npm

## Setup Instructions

Detailed notes are available in `Running.md`.

### 1. Clone and install dependencies

```bash
git clone https://github.com/RonyAbouEzzi/Web-2-Project.git
cd Web-2-Project
composer install
npm install
```

### 2. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Update `.env` values for:

- Database
- Mailtrap
- Google OAuth
- GitHub OAuth
- Google Maps
- Stripe
- Twilio (optional)
- Pusher (optional)

### 3. Prepare database and storage

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 4. Run the application

Terminal 1:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Terminal 2:

```bash
npm run dev
```

Terminal 3:

```bash
php artisan queue:work
```

Application URL: `http://127.0.0.1:8000`

### 5. Demo accounts (seeded)

- Admin: `admin@eservices.gov` / `password`
- Office Manager: `manager@beirut.gov.lb` / `password`
- Citizen: `citizen1@test.com` / `password`

## Screenshots

Add/update screenshots in `docs/screenshots/` and keep filenames consistent:

- `docs/screenshots/login.png`
- `docs/screenshots/admin-dashboard.png`
- `docs/screenshots/office-dashboard.png`
- `docs/screenshots/citizen-dashboard.png`
- `docs/screenshots/request-tracking.png`

Then render them in this section using Markdown images:

```md
![Login](docs/screenshots/login.png)
![Admin Dashboard](docs/screenshots/admin-dashboard.png)
![Office Dashboard](docs/screenshots/office-dashboard.png)
![Citizen Dashboard](docs/screenshots/citizen-dashboard.png)
![Request Tracking](docs/screenshots/request-tracking.png)
```

## Contribution Guidelines

### Branch and PR workflow

1. Do not push directly to `main`.
2. Pull latest changes:
   `git fetch origin`
3. Switch to your feature branch:
   `git checkout <your-feature-branch>`
4. Update your feature branch:
   `git pull origin <your-feature-branch>`
5. Commit and push your work:
   `git add .`
   `git commit -m "feat: short description"`
   `git push origin <your-feature-branch>`
6. Open a Pull Request from your feature branch into `dev`.
7. Require at least one teammate review before merge.
8. Merge `dev` into `main` only after validation/testing.

### Pull request checklist

Before opening PR:

- Feature works end-to-end locally
- No debug/temp files included
- No secrets committed (`.env`, keys, tokens)
- Migration/seed updates were tested
- PR description includes changes + test steps

## Security and Configuration Notes

- Keep `APP_DEBUG=true` only for local development.
- Never commit real credentials or API secrets.
- Use test keys for Stripe and sandbox services outside production.
