# School Hub - Laravel 11 Migration

This is the Laravel 11 version of the School Hub application, migrated from React/Node.js.

## Setup Instructions

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL
- Node.js and npm

### Installation

1. **Install PHP Dependencies**
   ```bash
   composer install
   ```

2. **Install Node Dependencies**
   ```bash
   npm install
   ```

3. **Configure Environment**
   - Copy `.env.example` to `.env` (if not exists)
   - Update database credentials in `.env`
   - Generate application key: `php artisan key:generate`

4. **Run Migrations**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build Assets**
   ```bash
   npm run build
   ```

6. **Start Development Server**
   ```bash
   php artisan serve
   npm run dev
   ```

## Project Structure

- `app/Http/Controllers/Admin/` - Admin controllers
- `app/Models/` - Eloquent models
- `database/migrations/` - Database migrations
- `resources/views/` - Blade templates
- `routes/web.php` - Web routes
- `routes/api.php` - API routes

## Features

All features from the original React app are preserved:
- Admin Dashboard with statistics and charts
- Student Management (CRUD)
- Teacher Management (CRUD)
- Course Management
- Exam Management
- Payment Management
- Attendance Management
- Student Dashboard
- Teacher Dashboard

## Notes

- The UI matches the original React application exactly
- All API endpoints maintain the same response format
- Database structure is identical to the original

