# Go-Live Checklist

## Environment

- [ ] Copy `.env.example` to `.env` on the server; never commit `.env`.
- [ ] Set `APP_ENV=production`, `APP_DEBUG=false`, a real `APP_URL`, and a generated `APP_KEY`.
- [ ] Configure production database variables and run migrations with `php artisan migrate --force`.
- [ ] Configure a real mailer, SMTP credentials, and `MAIL_FROM_ADDRESS`.
- [ ] Set `FILESYSTEM_DISK` deliberately and verify the selected storage credentials.
- [ ] Use secure cookies and HTTPS in production (`SESSION_SECURE_COOKIE=true`).

## Web Server And Storage

- [ ] Set the web server document root to the project `public/` directory only.
- [ ] Confirm `storage/app/private/` is outside the document root and has no public alias or symlink.
- [ ] Allow `public/storage` only for files intentionally stored on the public disk.
- [ ] Keep `.env`, `storage/`, `vendor/`, and project source files outside direct web access.
- [ ] Configure PHP-FPM/Apache limits for the application's upload size and timeout requirements.

## Build And Cache

- [ ] Run `composer install --no-dev --optimize-autoloader`.
- [ ] Run `npm ci && npm run build`.
- [ ] Run `php artisan config:cache`.
- [ ] Run `php artisan route:cache`.
- [ ] Run `php artisan view:cache`.
- [ ] Restart queue workers after deployment and verify the queue backend is reachable.

## Smoke Test

- [ ] Check login for admin, dosen, and mahasiswa accounts.
- [ ] Check the course, assignment, submission, and grading flow.
- [ ] Check authorized and unauthorized file downloads.
- [ ] Check mail delivery, logs, scheduled jobs, and queue processing.
- [ ] Confirm HTTPS, backups, monitoring, and rollback procedures before opening traffic.
