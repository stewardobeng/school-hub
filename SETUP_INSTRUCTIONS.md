# Complete Setup Instructions

## Overview

The School Hub application now has a complete Node.js/Express backend with MySQL database. All data is persisted and will survive page refreshes.

## What's Been Implemented

### Backend (Complete ✅)
- ✅ Express server with MySQL connection
- ✅ Database schema with all tables
- ✅ CRUD API routes for:
  - Students
  - Teachers
  - Courses
  - Exams
  - Payments
  - Attendance
- ✅ Dashboard statistics API
- ✅ Database migration script with seed data
- ✅ CORS configuration for frontend

### Frontend Integration (In Progress)
- ✅ API client service (`src/lib/api.ts`)
- ✅ Vite proxy configuration
- ✅ AdminStudents page fully integrated with API
- ⏳ Other admin pages need similar integration

## Setup Steps

### 1. Backend Setup

```bash
# Navigate to backend directory
cd backend

# Install dependencies
npm install

# Copy environment file
cp .env.example .env

# Edit .env with your MySQL credentials
# DB_HOST=localhost
# DB_USER=root
# DB_PASSWORD=your_password
# DB_NAME=school_hub

# Run database migration (creates tables and seeds data)
npm run db:migrate

# Start backend server
npm run dev
```

### 2. Frontend Setup

The frontend is already configured. Just make sure the backend is running first, then:

```bash
# From project root
npm run dev
```

The frontend will automatically proxy API requests to `http://localhost:3000`.

## Current Status

### Fully Integrated Pages
- ✅ **AdminStudents** - Complete CRUD operations with API

### Pages Needing Integration
- ⏳ AdminTeachers
- ⏳ AdminCourses
- ⏳ AdminExam
- ⏳ AdminPayment
- ⏳ AdminAttendance
- ⏳ AdminDashboard (for stats)
- ⏳ AdminStudentDetail
- ⏳ AdminTeacherDetail

## How to Integrate Remaining Pages

Each page needs similar updates to AdminStudents:

1. **Import the API client:**
   ```typescript
   import api from "@/lib/api";
   ```

2. **Replace hardcoded data with API calls:**
   ```typescript
   useEffect(() => {
     fetchData();
   }, []);

   const fetchData = async () => {
     const response = await api.getTeachers(); // or getCourses(), etc.
     if (response.success) {
       setTeachers(response.data);
     }
   };
   ```

3. **Update handlers to use API:**
   ```typescript
   const handleAdd = async (data) => {
     const response = await api.createTeacher(data);
     if (response.success) {
       fetchData(); // Refresh list
     }
   };
   ```

4. **Add loading states:**
   ```typescript
   const [isLoading, setIsLoading] = useState(true);
   ```

## Testing

1. Start backend: `cd backend && npm run dev`
2. Start frontend: `npm run dev`
3. Navigate to `http://localhost:8080/admin/students`
4. Try adding, editing, deleting students
5. Refresh page - data should persist!

## API Endpoints

All endpoints are available at `http://localhost:3000/api`:

- `/api/students` - Student management
- `/api/teachers` - Teacher management
- `/api/courses` - Course management
- `/api/exams` - Exam management
- `/api/payments` - Payment management
- `/api/attendance` - Attendance management
- `/api/dashboard/stats` - Dashboard statistics

## Database

The database includes:
- 8 main tables with relationships
- Foreign key constraints for data integrity
- Seed data for testing
- Auto-incrementing IDs and timestamps

## Next Steps

1. ✅ Backend is complete and ready
2. ✅ AdminStudents page is integrated
3. ⏳ Integrate remaining admin pages (follow AdminStudents pattern)
4. ⏳ Update detail pages to fetch from API
5. ⏳ Update dashboard to use API stats

## Notes

- All API responses follow a consistent format: `{ success: boolean, data: any }`
- Error handling is built into the API client
- The frontend uses React Query setup (already configured)
- CORS is enabled for localhost development

