# Care-Connect Hospital Management System

## Project Overview
Care-Connect is a Laravel-based hospital management system with Role-Based Access Control (RBAC) for Admin, Doctor, and Patient roles.

## Tech Stack
- **Backend:** Laravel 10 (PHP 8.1+)
- **Database:** MySQL
- **Frontend:** Blade Templates + Bootstrap 5
- **Authentication:** Laravel Breeze with custom RBAC

## Project Structure
- `app/Models/` - Eloquent models (User, Patient, Doctor, Schedule, Appointment)
- `app/Http/Controllers/` - Route controllers organized by module
- `app/Http/Middleware/` - Custom role-based middleware
- `database/migrations/` - Database schema definitions
- `resources/views/` - Blade templates organized by role/module
- `routes/web.php` - Application routes

## Roles & Permissions
1. **Admin:** Full system access, user management, appointment approval
2. **Doctor:** View own schedule, manage appointments, view patient history
3. **Patient:** Book appointments, view own records, update profile

## Key Features
- Smart appointment booking with conflict detection
- Doctor schedule management
- Patient record management with privacy controls
- Role-specific dashboards

## Development Commands
```bash
composer install          # Install dependencies
php artisan migrate       # Run database migrations
php artisan db:seed       # Seed initial data
php artisan serve         # Start development server
```

## Database Configuration
Configure `.env` file with MySQL credentials before running migrations.
