# School Hub - Complete School Management System

A comprehensive, full-stack school management system built with React, Node.js, Express, and MySQL. This application provides a complete solution for managing students, teachers, courses, exams, payments, and attendance in educational institutions.

![School Hub](https://img.shields.io/badge/Version-1.0.0-blue)
![License](https://img.shields.io/badge/License-MIT-green)
![Node](https://img.shields.io/badge/Node.js-16+-green)
![React](https://img.shields.io/badge/React-18.3+-blue)

## 🚀 Features

### Admin Dashboard
- **Real-time Statistics**: View total students, teachers, parents, and earnings
- **Interactive Charts**: Earnings, attendance, and performance analytics
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

### Frontend
- **React 18.3** - UI library
- **TypeScript** - Type safety
- **Vite** - Build tool and dev server
- **Tailwind CSS** - Styling
- **Shadcn/ui** - UI components
- **React Router** - Navigation
- **Recharts** - Data visualization
- **React Query** - Data fetching

### Backend
- **Node.js** - Runtime environment
- **Express.js** - Web framework
- **MySQL** - Database
- **mysql2** - MySQL driver
- **CORS** - Cross-origin resource sharing
- **dotenv** - Environment variables

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **Node.js** (v16 or higher) - [Download](https://nodejs.org/)
- **MySQL** (v8.0 or higher) - [Download](https://dev.mysql.com/downloads/)
- **npm** or **yarn** - Package manager
- **Git** - Version control

### For Windows Users
- **XAMPP** (includes MySQL) - [Download](https://www.apachefriends.org/)

## 🚀 Installation Guide

### Step 1: Clone the Repository

```bash
git clone https://github.com/stewardobeng/school-hub.git
cd school-hub
```

### Step 2: Install Frontend Dependencies

```bash
npm install
```

### Step 3: Install Backend Dependencies

```bash
cd backend
npm install
cd ..
```

### Step 4: Database Setup

1. **Start MySQL Server**
   - If using XAMPP: Start MySQL from XAMPP Control Panel
   - If using standalone MySQL: Ensure MySQL service is running

2. **Create Database** (Optional - migration script will create it)
   ```sql
   CREATE DATABASE school_hub;
   ```

3. **Configure Environment Variables**
   
   Create a `.env` file in the `backend` directory:
   ```bash
   cd backend
   cp .env.example .env
   ```
   
   Edit `backend/.env` with your MySQL credentials:
   ```env
   PORT=3000
   NODE_ENV=development
   DB_HOST=localhost
   DB_USER=root
   DB_PASSWORD=your_mysql_password
   DB_NAME=school_hub
   DB_PORT=3306
   JWT_SECRET=your-super-secret-jwt-key-change-this-in-production
   ```

4. **Run Database Migration**
   ```bash
   cd backend
   npm run db:migrate
   ```
   
   This will:
   - Create the database if it doesn't exist
   - Create all required tables
   - Seed initial data for testing

### Step 5: Start the Development Servers

**Terminal 1 - Backend Server:**
```bash
cd backend
npm run dev
```

**Terminal 2 - Frontend Server:**
```bash
npm run dev
```

### Step 6: Access the Application

- **Frontend**: http://localhost:8080
- **Backend API**: http://localhost:3000
- **API Health Check**: http://localhost:3000/api/health

## 📁 Project Structure

```
school-hub/
├── backend/                 # Backend API
│   ├── config/             # Configuration files
│   │   └── database.js     # MySQL connection
│   ├── routes/             # API routes
│   │   ├── students.js     # Student endpoints
│   │   ├── teachers.js     # Teacher endpoints
│   │   ├── courses.js      # Course endpoints
│   │   ├── exams.js         # Exam endpoints
│   │   ├── payments.js     # Payment endpoints
│   │   ├── attendance.js   # Attendance endpoints
│   │   └── dashboard.js    # Dashboard statistics
│   ├── scripts/            # Utility scripts
│   │   └── migrate.js      # Database migration
│   ├── server.js           # Express server
│   ├── package.json        # Backend dependencies
│   └── .env                # Environment variables
│
├── src/                    # Frontend source code
│   ├── components/         # React components
│   │   ├── dashboard/      # Dashboard components
│   │   ├── layout/         # Layout components
│   │   └── ui/             # UI components
│   ├── pages/              # Page components
│   │   └── admin/          # Admin pages
│   ├── lib/                # Utilities
│   │   ├── api.ts          # API client
│   │   └── utils.ts        # Helper functions
│   └── App.tsx             # Main app component
│
├── public/                 # Static assets
├── package.json           # Frontend dependencies
└── vite.config.ts         # Vite configuration
```

## 🔌 API Documentation

### Base URL
```
http://localhost:3000/api
```

### Endpoints

#### Students
- `GET /api/students` - Get all students
- `GET /api/students/:id` - Get student by ID
- `POST /api/students` - Create new student
- `PUT /api/students/:id` - Update student
- `DELETE /api/students/:id` - Delete student

#### Teachers
- `GET /api/teachers` - Get all teachers
- `GET /api/teachers/:id` - Get teacher by ID
- `POST /api/teachers` - Create new teacher
- `PUT /api/teachers/:id` - Update teacher
- `DELETE /api/teachers/:id` - Delete teacher

#### Courses
- `GET /api/courses` - Get all courses
- `GET /api/courses/:id` - Get course by ID
- `POST /api/courses` - Create new course
- `PUT /api/courses/:id` - Update course
- `DELETE /api/courses/:id` - Delete course

#### Exams
- `GET /api/exams` - Get all exams
- `GET /api/exams/:id` - Get exam by ID
- `POST /api/exams` - Create new exam
- `PUT /api/exams/:id` - Update exam
- `DELETE /api/exams/:id` - Delete exam

#### Payments
- `GET /api/payments` - Get all payments
- `GET /api/payments/:id` - Get payment by ID
- `POST /api/payments` - Create new payment
- `PUT /api/payments/:id` - Update payment
- `DELETE /api/payments/:id` - Delete payment

#### Attendance
- `GET /api/attendance` - Get all attendance records
- `GET /api/attendance/:id` - Get attendance by ID
- `POST /api/attendance` - Create new attendance record
- `PUT /api/attendance/:id` - Update attendance record
- `DELETE /api/attendance/:id` - Delete attendance record

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

See `backend/scripts/migrate.js` for complete schema definition.

## 🚀 Deployment Guide

### Frontend Deployment (Vercel/Netlify)

1. **Build the frontend:**
   ```bash
   npm run build
   ```

2. **Deploy to Vercel:**
   ```bash
   npm i -g vercel
   vercel
   ```

3. **Set Environment Variables:**
   - `VITE_API_URL`: Your backend API URL (e.g., `https://your-api.com/api`)

### Backend Deployment (Railway/Render/Heroku)

1. **Set Environment Variables:**
   ```env
   PORT=3000
   NODE_ENV=production
   DB_HOST=your-database-host
   DB_USER=your-database-user
   DB_PASSWORD=your-database-password
   DB_NAME=school_hub
   DB_PORT=3306
   JWT_SECRET=your-production-secret-key
   ```

2. **Deploy:**
   - Push your code to GitHub
   - Connect your repository to your hosting platform
   - Set environment variables in platform settings
   - Deploy

3. **Run Migration:**
   ```bash
   npm run db:migrate
   ```

### Database Deployment

For production, consider using:
- **MySQL** (managed service like AWS RDS, PlanetScale, or DigitalOcean)
- **PostgreSQL** (requires schema migration)

## 🧪 Testing

### Test API Endpoints

```bash
# Health check
curl http://localhost:3000/api/health

# Get all students
curl http://localhost:3000/api/students

# Get all teachers
curl http://localhost:3000/api/teachers
```

## 📝 Available Scripts

### Frontend
- `npm run dev` - Start development server
- `npm run build` - Build for production
- `npm run preview` - Preview production build
- `npm run lint` - Run ESLint

### Backend
- `npm run dev` - Start development server with nodemon
- `npm start` - Start production server
- `npm run db:migrate` - Run database migration

## 🔧 Configuration

### Vite Proxy Configuration

The frontend is configured to proxy API requests to the backend during development:

```typescript
// vite.config.ts
proxy: {
  '/api': {
    target: 'http://localhost:3000',
    changeOrigin: true,
  },
}
```

### CORS Configuration

The backend allows CORS from the frontend origin. Update `backend/server.js` for production:

```javascript
app.use(cors({
  origin: process.env.FRONTEND_URL || 'http://localhost:8080'
}));
```

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
   - Ensure `DB_USER` and `DB_PASSWORD` are correct
   - Check `DB_HOST` and `DB_PORT`

3. **Test connection:**
   ```bash
   mysql -u root -p
   ```

### Port Already in Use

If port 3000 or 8080 is already in use:

1. **Change backend port:**
   ```env
   PORT=3001
   ```

2. **Update Vite proxy:**
   ```typescript
   target: 'http://localhost:3001'
   ```

### Migration Errors

1. **Drop and recreate database:**
   ```sql
   DROP DATABASE school_hub;
   CREATE DATABASE school_hub;
   ```

2. **Run migration again:**
   ```bash
   npm run db:migrate
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

- [Shadcn/ui](https://ui.shadcn.com/) - Beautiful UI components
- [Vite](https://vitejs.dev/) - Next generation frontend tooling
- [Express.js](https://expressjs.com/) - Fast, unopinionated web framework
- [React](https://react.dev/) - A JavaScript library for building user interfaces

## 📞 Support

For support, email your-email@example.com or open an issue in the repository.

---

**Made with ❤️ for educational institutions**
