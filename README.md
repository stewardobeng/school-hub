# School Hub - Complete School Management System

A comprehensive, full-stack school management system built with Laravel 11, Blade templates, and MySQL. This application provides a complete solution for managing students, teachers, courses, exams, payments, and attendance in educational institutions.

![School Hub](https://img.shields.io/badge/Version-1.0.0-blue)
![License](https://img.shields.io/badge/License-MIT-green)
![Laravel](https://img.shields.io/badge/Laravel-11-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)

## 🚀 Features

### Admin Dashboard
- **Real-time Statistics**: View total students, teachers, parents, and earnings
- **Interactive Charts**: Earnings, attendance, and performance analytics using Chart.js
- **Event Calendar**: Track upcoming exams and events
- **Top Performers**: Identify and track high-achieving students

### Student Management
- Complete CRUD operations for student records
- Student profile pages with detailed information
- Grade tracking and exam results
- Attendance monitoring
- Payment history

### Teacher Management
- Teacher profiles and schedules
- Class assignments
- Student roster management
- Attendance tracking

### Course Management
- Course creation and management
- Student enrollment
- Schedule management
- Course analytics

### Exam Management
- Exam scheduling
- Result tracking
- Grade management
- Performance analytics

### Payment Management
- Payment recording and tracking
- Multiple payment methods
- Payment history
- Receipt generation

### Attendance Management
- Daily attendance tracking
- Class-wise attendance reports
- Attendance analytics

## 🛠️ Technology Stack

### Backend
- **Laravel 11** - PHP web framework
- **MySQL** - Database
- **Eloquent ORM** - Database abstraction
- **Blade Templates** - Templating engine

### Frontend
- **Blade Templates** - Server-side rendering
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Chart.js** - Data visualization
- **Vite** - Frontend build tool

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP** (v8.2 or higher) - [Download](https://www.php.net/downloads.php)
- **Composer** - PHP dependency manager - [Download](https://getcomposer.org/)
- **MySQL** (v8.0 or higher) - [Download](https://dev.mysql.com/downloads/)
- **Node.js** (v16 or higher) - For frontend assets - [Download](https://nodejs.org/)
- **npm** or **yarn** - Package manager
- **Git** - Version control

### For Windows Users
- **XAMPP** (includes PHP and MySQL) - [Download](https://www.apachefriends.org/)

## 🚀 Installation Guide

### Step 1: Clone the Repository

```bash
git clone https://github.com/stewardobeng/school-hub.git
cd school-hub
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Install Frontend Dependencies

```bash
npm install
```

### Step 4: Database Setup

1. **Start MySQL Server**
   - If using XAMPP: Start MySQL from XAMPP Control Panel
   - If using standalone MySQL: Ensure MySQL service is running

2. **Create Database**
   ```sql
   CREATE DATABASE school_hub;
   ```

3. **Configure Environment Variables**
   
   Copy the `.env.example` file to `.env`:
   ```bash
   cp .env.example .env
   ```
   
   Edit `.env` with your MySQL credentials:
   ```env
   APP_NAME="School Hub"
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost/school-hub/public
   
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=school_hub
   DB_USERNAME=root
   DB_PASSWORD=your_mysql_password
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Run Database Migrations**
   ```bash
   php artisan migrate
   ```
   
   This will create all required tables.

6. **Seed Initial Data** (Optional)
   ```bash
   php artisan db:seed
   ```
   
   This will populate the database with sample data for testing.

### Step 5: Build Frontend Assets

```bash
npm run build
```

Or for development with hot reload:

```bash
npm run dev
```

### Step 6: Start the Development Server

**For XAMPP Users:**
- Start Apache and MySQL from XAMPP Control Panel
- Access the application at: `http://localhost/school-hub/public`

**For Laravel's Built-in Server:**
```bash
php artisan serve
```
- Access the application at: `http://localhost:8000`

## 📁 Project Structure

```
school-hub/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Admin/          # Admin controllers
│   ├── Models/                 # Eloquent models
│   └── Services/               # Business logic services
│
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
│
├── resources/
│   ├── views/                  # Blade templates
│   │   ├── admin/              # Admin views
│   │   ├── student/            # Student views
│   │   ├── teacher/            # Teacher views
│   │   ├── components/         # Reusable components
│   │   └── layouts/            # Layout templates
│   ├── css/                    # CSS files
│   └── js/                     # JavaScript files
│
├── routes/
│   ├── web.php                 # Web routes
│   └── api.php                 # API routes
│
├── public/                     # Public assets
├── config/                     # Configuration files
├── storage/                    # Storage and logs
└── vendor/                     # Composer dependencies
```

## 🔌 API Documentation

### Base URL
```
http://localhost/school-hub/public/api
```

### Endpoints

#### Students
- `GET /api/students` - Get all students
- `GET /api/students/{id}` - Get student by ID
- `POST /api/students` - Create new student
- `PUT /api/students/{id}` - Update student
- `DELETE /api/students/{id}` - Delete student

#### Teachers
- `GET /api/teachers` - Get all teachers
- `GET /api/teachers/{id}` - Get teacher by ID
- `POST /api/teachers` - Create new teacher
- `PUT /api/teachers/{id}` - Update teacher
- `DELETE /api/teachers/{id}` - Delete teacher

#### Courses
- `GET /api/courses` - Get all courses
- `GET /api/courses/{id}` - Get course by ID
- `POST /api/courses` - Create new course
- `PUT /api/courses/{id}` - Update course
- `DELETE /api/courses/{id}` - Delete course

#### Exams
- `GET /api/exams` - Get all exams
- `GET /api/exams/{id}` - Get exam by ID
- `POST /api/exams` - Create new exam
- `PUT /api/exams/{id}` - Update exam
- `DELETE /api/exams/{id}` - Delete exam

#### Payments
- `GET /api/payments` - Get all payments
- `GET /api/payments/{id}` - Get payment by ID
- `POST /api/payments` - Create new payment
- `PUT /api/payments/{id}` - Update payment
- `DELETE /api/payments/{id}` - Delete payment

#### Attendance
- `GET /api/attendance` - Get all attendance records
- `GET /api/attendance/{id}` - Get attendance by ID
- `POST /api/attendance` - Create new attendance record
- `PUT /api/attendance/{id}` - Update attendance record
- `DELETE /api/attendance/{id}` - Delete attendance record

#### Dashboard
- `GET /api/dashboard/stats` - Get dashboard statistics

### Response Format

**Success Response:**
```json
{
  "success": true,
  "data": { ... }
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Error message",
  "error": "Detailed error information"
}
```

## 🗄️ Database Schema

The application uses the following main tables:

- **students** - Student information
- **teachers** - Teacher information
- **courses** - Course details
- **exams** - Exam schedules
- **payments** - Payment records
- **attendance** - Attendance records
- **course_enrollments** - Student-course relationships
- **exam_results** - Exam results

See `database/migrations/` for complete schema definition.

## 🚀 Deployment Guide

### Production Setup

1. **Set Environment Variables:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   
   DB_CONNECTION=mysql
   DB_HOST=your-database-host
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

2. **Optimize Application:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Build Frontend Assets:**
   ```bash
   npm run build
   ```

4. **Set Permissions:**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

### Database Deployment

For production, consider using:
- **MySQL** (managed service like AWS RDS, PlanetScale, or DigitalOcean)
- **PostgreSQL** (requires schema migration)

## 🧪 Testing

### Test API Endpoints

```bash
# Health check
curl http://localhost/school-hub/public/api/health

# Get all students
curl http://localhost/school-hub/public/api/students

# Get all teachers
curl http://localhost/school-hub/public/api/teachers
```

## 📝 Available Scripts

### Laravel Commands
- `php artisan serve` - Start development server
- `php artisan migrate` - Run database migrations
- `php artisan db:seed` - Seed database
- `php artisan route:list` - List all routes
- `php artisan tinker` - Interactive shell

### Frontend
- `npm run dev` - Start development server with hot reload
- `npm run build` - Build for production
- `npm run preview` - Preview production build

## 🔧 Configuration

### Laravel Configuration

Key configuration files:
- `config/app.php` - Application settings
- `config/database.php` - Database configuration
- `config/session.php` - Session configuration

### Vite Configuration

Frontend assets are compiled using Vite. Configuration is in `vite.config.js`.

## 🐛 Troubleshooting

### Database Connection Issues

1. **Check MySQL is running:**
   ```bash
   # Windows (XAMPP)
   # Check XAMPP Control Panel
   
   # Linux/Mac
   sudo systemctl status mysql
   ```

2. **Verify credentials in `.env`:**
   - Ensure `DB_USERNAME` and `DB_PASSWORD` are correct
   - Check `DB_HOST` and `DB_PORT`

3. **Test connection:**
   ```bash
   mysql -u root -p
   ```

### Permission Issues

If you encounter permission errors:

```bash
chmod -R 775 storage bootstrap/cache
```

### Migration Errors

1. **Drop and recreate database:**
   ```sql
   DROP DATABASE school_hub;
   CREATE DATABASE school_hub;
   ```

2. **Run migration again:**
   ```bash
   php artisan migrate:fresh --seed
   ```

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License.

## 👨‍💻 Author

**Steward Obeng**
- GitHub: [@stewardobeng](https://github.com/stewardobeng)
- Repository: [school-hub](https://github.com/stewardobeng/school-hub)

## 🙏 Acknowledgments

- [Laravel](https://laravel.com/) - The PHP Framework for Web Artisans
- [Tailwind CSS](https://tailwindcss.com/) - A utility-first CSS framework
- [Alpine.js](https://alpinejs.dev/) - A minimal framework for composing JavaScript behavior
- [Chart.js](https://www.chartjs.org/) - Simple yet flexible JavaScript charting

## 📞 Support

For support, email your-email@example.com or open an issue in the repository.

---

**Made with ❤️ for educational institutions**
