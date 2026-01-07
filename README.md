# Care-Connect: Hospital Management System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
</p>

A comprehensive Hospital Management and Appointment Booking System built with Laravel, featuring Role-Based Access Control (RBAC) for Admin, Doctor, and Patient roles.

## 🏥 Features

### For Administrators
- **Dashboard Overview**: Real-time statistics on users, appointments, and system activity
- **User Management**: Create, edit, and manage all users (doctors, patients, admins)
- **Appointment Management**: View and manage all appointments across the system
- **System-wide Reporting**: Access to comprehensive system analytics

### For Doctors
- **Personal Dashboard**: View today's appointments, upcoming schedule, and quick stats
- **Schedule Management**: Set available time slots for each day of the week
- **Appointment Handling**: Approve, reject, or complete patient appointments
- **Patient History**: View complete appointment history for each patient
- **Consultation Notes**: Add diagnosis and prescriptions after appointments

### For Patients
- **Easy Booking**: Book appointments with available doctors in a few clicks
- **Smart Scheduling**: View available time slots based on doctor schedules
- **Appointment Tracking**: Track appointment status (pending, approved, completed)
- **Profile Management**: Manage personal and medical information
- **Appointment History**: View past and upcoming appointments

### Smart Features
- **Conflict Detection**: Automatic prevention of double-booking
- **Dynamic Time Slots**: Real-time availability based on doctor schedules
- **Role-based Dashboards**: Customized experience for each user type
- **Responsive Design**: Works seamlessly on desktop and mobile devices

## 🛠️ Tech Stack

- **Backend**: Laravel 10 (PHP 8.1+)
- **Database**: MySQL 8.0+
- **Frontend**: Blade Templates + Bootstrap 5.3
- **Icons**: Bootstrap Icons
- **Authentication**: Laravel Breeze-style custom auth

## 📁 Project Structure

```
hospital_management_system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── Doctor/         # Doctor controllers
│   │   │   ├── Patient/        # Patient controllers
│   │   │   └── Auth/           # Authentication controllers
│   │   └── Middleware/         # Custom role-based middleware
│   └── Models/                 # Eloquent models
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/views/
│   ├── admin/                  # Admin views
│   ├── doctor/                 # Doctor views
│   ├── patient/                # Patient views
│   ├── auth/                   # Authentication views
│   └── layouts/                # Layout templates
├── routes/
│   └── web.php                 # Application routes
└── config/                     # Configuration files
```

## 🚀 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM (optional, for asset compilation)

### Step 1: Clone the Repository
```bash
git clone https://github.com/yourusername/hospital_management_system.git
cd hospital_management_system
```

### Step 2: Install Dependencies
```bash
composer install
```

### Step 3: Environment Setup
```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database
Edit the `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=care_connect
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 5: Create Database
```sql
CREATE DATABASE care_connect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 6: Run Migrations & Seeders
```bash
# Run database migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### Step 7: Start the Development Server
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## 👤 Default Login Credentials

After seeding, you can use these credentials:

| Role    | Email                          | Password     |
|---------|--------------------------------|--------------|
| Admin   | admin@careconnect.com          | password123  |
| Doctor  | sarah.johnson@careconnect.com  | password123  |
| Doctor  | michael.chen@careconnect.com   | password123  |
| Patient | john.smith@example.com         | password123  |
| Patient | jane.doe@example.com           | password123  |

## 📋 Database Schema

### Users Table
- Basic user info (name, email, password, role)
- Contact information (phone, address)
- Profile data (gender, date of birth)

### Doctors Table
- Specialization, qualification, license number
- Consultation fee, experience years
- Bio and availability status

### Patients Table
- Medical info (blood type, height, weight, allergies)
- Emergency contact information
- Insurance details

### Schedules Table
- Day of week availability
- Start and end times
- Slot duration (minutes)

### Appointments Table
- Patient and doctor references
- Date, time, and status
- Reason, notes, diagnosis, prescription

## 🔐 Role-Based Access

### Routes Protection
```php
// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(...)

// Doctor routes  
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->group(...)

// Patient routes
Route::middleware(['auth', 'role:patient'])->prefix('patient')->group(...)
```

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run tests with coverage
php artisan test --coverage
```

## 📝 Common Commands

```bash
# Clear application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Regenerate autoload files
composer dump-autoload

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Bootstrap](https://getbootstrap.com) - CSS Framework
- [Bootstrap Icons](https://icons.getbootstrap.com) - Icon Library

---

<p align="center">
  Made with ❤️ for Healthcare
</p>
