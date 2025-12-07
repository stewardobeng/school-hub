# Backend Setup Guide

This guide will help you set up the Node.js/Express backend with MySQL for the School Hub application.

## Quick Start

### Step 1: Install Backend Dependencies

```bash
cd backend
npm install
```

### Step 2: Set Up MySQL Database

1. Make sure MySQL is running on your system
2. Create a database (or use existing):
   ```sql
   CREATE DATABASE school_hub;
   ```

### Step 3: Configure Environment Variables

1. Copy the example environment file:
   ```bash
   cd backend
   cp .env.example .env
   ```

2. Edit `.env` and update with your MySQL credentials:
   ```env
   DB_HOST=localhost
   DB_USER=root
   DB_PASSWORD=your_mysql_password
   DB_NAME=school_hub
   DB_PORT=3306
   PORT=3000
   ```

### Step 4: Run Database Migration

This creates all tables and seeds initial data:

```bash
cd backend
npm run db:migrate
```

### Step 5: Start the Backend Server

```bash
cd backend
npm run dev
```

The API will be running at `http://localhost:3000`

### Step 6: Start the Frontend

In a new terminal:

```bash
npm run dev
```

The frontend will be running at `http://localhost:8080` and will automatically proxy API requests to the backend.

## Troubleshooting

### Database Connection Error

- Verify MySQL is running: `mysql -u root -p`
- Check your `.env` file has correct credentials
- Ensure the database `school_hub` exists

### Port Already in Use

If port 3000 is already in use, change it in `backend/.env`:
```env
PORT=3001
```

And update `vite.config.ts` proxy target:
```typescript
proxy: {
  '/api': {
    target: 'http://localhost:3001',
    changeOrigin: true,
  },
}
```

### Migration Errors

If migration fails:
1. Drop the database: `DROP DATABASE school_hub;`
2. Recreate it: `CREATE DATABASE school_hub;`
3. Run migration again: `npm run db:migrate`

## Testing the API

You can test the API using curl or Postman:

```bash
# Health check
curl http://localhost:3000/api/health

# Get all students
curl http://localhost:3000/api/students

# Get all teachers
curl http://localhost:3000/api/teachers
```

## Project Structure

```
backend/
├── config/
│   └── database.js       # MySQL connection pool
├── routes/
│   ├── students.js       # Student CRUD operations
│   ├── teachers.js       # Teacher CRUD operations
│   ├── courses.js        # Course CRUD operations
│   ├── exams.js          # Exam CRUD operations
│   ├── payments.js       # Payment CRUD operations
│   ├── attendance.js     # Attendance CRUD operations
│   └── dashboard.js      # Dashboard statistics
├── scripts/
│   └── migrate.js        # Database migration and seeding
├── server.js             # Express server setup
├── package.json
└── .env                  # Environment variables (create from .env.example)
```

## Next Steps

Once the backend is running:
1. The frontend will automatically connect to the API
2. All data operations (add, edit, delete) will persist to MySQL
3. Data will survive page refreshes and server restarts

