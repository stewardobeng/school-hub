# School Hub Backend API

A Node.js/Express backend API for the School Hub Management System with MySQL database.

## Prerequisites

- Node.js (v16 or higher)
- MySQL (v8.0 or higher)
- npm or yarn

## Setup Instructions

### 1. Install Dependencies

```bash
cd backend
npm install
```

### 2. Configure Database

1. Create a MySQL database (or use existing one):
```sql
CREATE DATABASE school_hub;
```

2. Copy the environment file:
```bash
cp .env.example .env
```

3. Update `.env` with your database credentials:
```env
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=your_password
DB_NAME=school_hub
DB_PORT=3306
PORT=3000
NODE_ENV=development
```

### 3. Run Database Migration

This will create all tables and seed initial data:

```bash
npm run db:migrate
```

### 4. Start the Server

**Development mode (with auto-reload):**
```bash
npm run dev
```

**Production mode:**
```bash
npm start
```

The API will be available at `http://localhost:3000`

## API Endpoints

### Health Check
- `GET /api/health` - Check API status

### Students
- `GET /api/students` - Get all students (with optional query params: search, status, grade)
- `GET /api/students/:id` - Get single student by ID
- `POST /api/students` - Create new student
- `PUT /api/students/:id` - Update student
- `DELETE /api/students/:id` - Delete student

### Teachers
- `GET /api/teachers` - Get all teachers (with optional query params: search, status, subject)
- `GET /api/teachers/:id` - Get single teacher by ID
- `POST /api/teachers` - Create new teacher
- `PUT /api/teachers/:id` - Update teacher
- `DELETE /api/teachers/:id` - Delete teacher

### Courses
- `GET /api/courses` - Get all courses (with optional query params: search, status, grade, teacher_id)
- `GET /api/courses/:id` - Get single course by ID
- `POST /api/courses` - Create new course
- `PUT /api/courses/:id` - Update course
- `DELETE /api/courses/:id` - Delete course

### Exams
- `GET /api/exams` - Get all exams (with optional query params: search, status, type, course_id, grade)
- `GET /api/exams/:id` - Get single exam by ID
- `POST /api/exams` - Create new exam
- `PUT /api/exams/:id` - Update exam
- `DELETE /api/exams/:id` - Delete exam

### Payments
- `GET /api/payments` - Get all payments (with optional query params: search, status, type, student_id, start_date, end_date)
- `GET /api/payments/:id` - Get single payment by ID
- `POST /api/payments` - Create new payment
- `PUT /api/payments/:id` - Update payment
- `DELETE /api/payments/:id` - Delete payment

### Attendance
- `GET /api/attendance` - Get all attendance records (with optional query params: search, status, teacher_id, start_date, end_date, class_name)
- `GET /api/attendance/:id` - Get single attendance record by ID
- `POST /api/attendance` - Create new attendance record
- `PUT /api/attendance/:id` - Update attendance record
- `DELETE /api/attendance/:id` - Delete attendance record

### Dashboard
- `GET /api/dashboard/stats` - Get dashboard statistics

## Database Schema

The migration script creates the following tables:
- `students` - Student information
- `teachers` - Teacher information
- `courses` - Course information
- `exams` - Exam schedules and details
- `payments` - Payment records
- `attendance` - Attendance records
- `course_enrollments` - Student-course relationships
- `exam_results` - Exam results for students

## Response Format

All API responses follow this format:

**Success:**
```json
{
  "success": true,
  "data": { ... }
}
```

**Error:**
```json
{
  "success": false,
  "message": "Error message",
  "error": "Detailed error information"
}
```

## Development

The server uses `nodemon` for auto-reloading during development. Any changes to the code will automatically restart the server.

## Notes

- The API uses CORS to allow requests from the frontend
- All timestamps are stored in UTC
- Foreign key constraints ensure data integrity
- The migration script includes seed data for testing

