# E-Services Management Platform

Web Programming 2 team project (Laravel 12).

Detailed local setup and deployment notes are in `Running.md`.

## Team Branches

Each member works only on their assigned feature branch:

- Rony (Team Lead): `feature/auth-realtime`
- Ahmad: `feature/admin-panel`
- Hasouna: `feature/office-panel`
- Hussein: `feature/citizen-portal`
- Maged: `feature/chat-maps-ui`

Core branches:

- `main`: production-ready only
- `dev`: integration branch

## Git Workflow (Mandatory)

1. Do not push directly to `main`.
2. Pull latest changes before coding:
   - `git fetch origin`
3. Switch to your branch:
   - `git checkout <your-feature-branch>`
4. Pull latest on your branch:
   - `git pull origin <your-feature-branch>`
5. Work, commit, and push:
   - `git add .`
   - `git commit -m "feat: <what you implemented>"`
   - `git push origin <your-feature-branch>`
6. Open Pull Request:
   - Source: your feature branch
   - Target: `dev`
7. At least 1 teammate review is required before merge.
8. Team lead merges `dev` -> `main` only after testing.

## Quick Start (Local Setup)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve --host=127.0.0.1 --port=8000
```

App URL:

- `http://127.0.0.1:8000`

## Branch Protection (GitHub)

Apply to both `main` and `dev`:

1. Require pull request before merging
2. Require at least 1 approval
3. Dismiss stale approvals on new commits
4. Require conversation resolution before merge
5. Block force pushes
6. Block deletion

## Pull Request Checklist

Before opening PR to `dev`, confirm:

1. Feature works locally end-to-end
2. No debug/temp files committed
3. `.env` secrets are not committed
4. Migration/seed changes are tested
5. PR description includes what changed and how to test

## Current Feature Branches in Repo

- `feature/auth-realtime`
- `feature/admin-panel`
- `feature/office-panel`
- `feature/citizen-portal`
- `feature/chat-maps-ui`
