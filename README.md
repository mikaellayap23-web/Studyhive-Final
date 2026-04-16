# Studyhive - CSS NC II Training Platform

A web-based Learning Management System (LMS) built with Laravel 12 for CSS NC II (Computer Systems Servicing National Certificate II) training. Supports three roles: **Admin**, **Teacher**, and **Student**.

## Features

- Module management with PDF materials and image uploads
- Assessment system with multiple-choice questions, scoring, and multiple attempts
- Student enrollment and progress tracking (PDF page-by-page)
- Auto-generated certificates on module completion (PDF download)
- Public certificate verification
- Announcements with read tracking
- Admin user management with approval workflow
- Audit trail logging
- Role-based access control

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ and npm
- MySQL 8.0+ (or MariaDB)
- Git

## Setup Instructions

### 1. Clone the repository

```bash
git clone https://github.com/mikaellayap23-web/Studyhive-Final.git
cd Studyhive-Final
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node.js dependencies

```bash
npm install
```

### 4. Configure environment

Copy the example environment file and generate an app key:

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure your `.env` file

Open `.env` and update these settings:

```env
APP_NAME=Studyhive
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=studyhive_db
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Create the database

Create a MySQL database named `studyhive_db` (or whatever you set in `.env`):

```sql
CREATE DATABASE studyhive_db;
```

### 7. Run migrations

```bash
php artisan migrate
```

### 8. (Optional) Seed the database

```bash
php artisan db:seed
```

This creates default test accounts:
- Admin: `admin@studyhive.com`
- Teacher: `teacher@studyhive.com`
- Student: `student@studyhive.com`

### 9. Create the storage symlink

```bash
php artisan storage:link
```

This allows uploaded files (module images, PDFs, certificates) to be served publicly.

### 10. Build frontend assets

```bash
npm run build
```

### 11. Start the development server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Deploying with Cloudflare Tunnel

To make the app accessible over the internet using Cloudflare Tunnel:

### 1. Update your `.env` for production

```env
APP_NAME=Studyhive
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-tunnel-url.trycloudflare.com
FORCE_HTTPS=true
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
LOG_LEVEL=error
```

### 2. Cache the config

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Start the server

```bash
php artisan serve
```

### 4. Start the Cloudflare tunnel (in a separate terminal)

```bash
cloudflared tunnel --url http://localhost:8000
```

Cloudflare will output a URL like `https://random-words.trycloudflare.com`. Update `APP_URL` in `.env` with this URL, then run:

```bash
php artisan config:cache
```

**Note:** Quick tunnel URLs change every time you restart `cloudflared`. For a permanent URL, set up a [named tunnel](https://developers.cloudflare.com/cloudflare-one/connections/connect-apps) with a Cloudflare account.

## Email Configuration (Gmail SMTP)

To enable email notifications (account creation, approval, certificates):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Studyhive"
```

Use a [Gmail App Password](https://support.google.com/accounts/answer/185833), not your regular Gmail password.

## User Roles

| Role | Access |
|------|--------|
| **Admin** | Full system control: user management, all modules, certificates, audit trail |
| **Teacher** | Create/manage assigned modules, view student progress, create assessments |
| **Student** | Enroll in modules, read materials, take assessments, earn certificates |

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Blade templates, Tailwind CSS 4, Vite 7
- **Database:** MySQL / MariaDB
- **PDF Generation:** DomPDF (barryvdh/laravel-dompdf)
- **Auth:** Session-based with role-based access control

## Project Structure

```
app/
  Http/Controllers/    # Route handlers
  Models/              # Eloquent models
  Services/            # Business logic (CertificateService)
  Mail/                # Mailable classes
  Http/Middleware/      # Custom middleware (EnsureUserIsAdmin, SecurityHeaders)
database/
  migrations/          # Database schema
  seeders/             # Test data
resources/
  views/               # Blade templates
  css/                 # Tailwind source
routes/
  web.php              # All routes
```

## License

This project is for educational purposes as part of CSS NC II training.
