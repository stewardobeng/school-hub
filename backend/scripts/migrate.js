import pool from '../config/database.js';
import dotenv from 'dotenv';

dotenv.config();

const createDatabase = async () => {
  try {
    // First, connect without specifying database
    const mysql = await import('mysql2/promise');
    const tempConnection = await mysql.createConnection({
      host: process.env.DB_HOST || 'localhost',
      user: process.env.DB_USER || 'root',
      password: process.env.DB_PASSWORD || '',
      port: process.env.DB_PORT || 3306,
    });
    
    // Create database if it doesn't exist
    await tempConnection.query(`CREATE DATABASE IF NOT EXISTS ${process.env.DB_NAME || 'school_hub'}`);
    await tempConnection.end();
    
    // Now connect to the created database
    const connection = await pool.getConnection();
    await connection.query(`USE ${process.env.DB_NAME || 'school_hub'}`);
    connection.release();
    console.log('✅ Database created/verified');
  } catch (error) {
    console.error('Error creating database:', error);
    process.exit(1);
  }
};

const createTables = async () => {
  try {
    const connection = await pool.getConnection();
    
    // Students table
    await connection.query(`
      CREATE TABLE IF NOT EXISTS students (
        id VARCHAR(20) PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        phone VARCHAR(20),
        grade VARCHAR(20) NOT NULL,
        status ENUM('Active', 'Inactive') DEFAULT 'Active',
        enrollment_date DATE NOT NULL,
        date_of_birth DATE,
        address TEXT,
        parent_name VARCHAR(200),
        parent_email VARCHAR(255),
        parent_phone VARCHAR(20),
        avatar_url VARCHAR(500),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
      )
    `);

    // Teachers table
    await connection.query(`
      CREATE TABLE IF NOT EXISTS teachers (
        id VARCHAR(20) PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        phone VARCHAR(20),
        subject VARCHAR(100) NOT NULL,
        status ENUM('Active', 'On Leave', 'Inactive') DEFAULT 'Active',
        join_date DATE NOT NULL,
        date_of_birth DATE,
        address TEXT,
        education TEXT,
        experience VARCHAR(50),
        avatar_url VARCHAR(500),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
      )
    `);

    // Courses table
    await connection.query(`
      CREATE TABLE IF NOT EXISTS courses (
        id VARCHAR(20) PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        code VARCHAR(50) UNIQUE NOT NULL,
        grade VARCHAR(20) NOT NULL,
        teacher_id VARCHAR(20),
        credits INT NOT NULL,
        duration VARCHAR(50) NOT NULL,
        schedule TEXT,
        status ENUM('Active', 'Archived') DEFAULT 'Active',
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
      )
    `);

    // Exams table
    await connection.query(`
      CREATE TABLE IF NOT EXISTS exams (
        id VARCHAR(20) PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        course_id VARCHAR(20),
        grade VARCHAR(20) NOT NULL,
        exam_date DATE NOT NULL,
        exam_time TIME NOT NULL,
        duration VARCHAR(50) NOT NULL,
        type ENUM('Midterm', 'Final', 'Quiz', 'Unit Test', 'Practical', 'Assessment') NOT NULL,
        status ENUM('Scheduled', 'In Progress', 'Completed', 'Cancelled') DEFAULT 'Scheduled',
        max_score INT DEFAULT 100,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL
      )
    `);

    // Payments table
    await connection.query(`
      CREATE TABLE IF NOT EXISTS payments (
        id VARCHAR(20) PRIMARY KEY,
        student_id VARCHAR(20) NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        type ENUM('Tuition Fee', 'Library Fee', 'Exam Fee', 'Other') NOT NULL,
        method ENUM('Credit Card', 'Bank Transfer', 'Cash', 'Online Payment') NOT NULL,
        payment_date DATE NOT NULL,
        status ENUM('Paid', 'Pending', 'Failed', 'Refunded') DEFAULT 'Pending',
        transaction_id VARCHAR(100) UNIQUE,
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
      )
    `);

    // Attendance table
    await connection.query(`
      CREATE TABLE IF NOT EXISTS attendance (
        id VARCHAR(20) PRIMARY KEY,
        class_name VARCHAR(255) NOT NULL,
        teacher_id VARCHAR(20),
        attendance_date DATE NOT NULL,
        total_students INT NOT NULL,
        present INT NOT NULL,
        absent INT NOT NULL,
        late INT DEFAULT 0,
        status ENUM('Completed', 'Pending') DEFAULT 'Pending',
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
      )
    `);

    // Course enrollments (many-to-many relationship)
    await connection.query(`
      CREATE TABLE IF NOT EXISTS course_enrollments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id VARCHAR(20) NOT NULL,
        course_id VARCHAR(20) NOT NULL,
        enrollment_date DATE NOT NULL,
        status ENUM('Active', 'Completed', 'Dropped') DEFAULT 'Active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
        UNIQUE KEY unique_enrollment (student_id, course_id)
      )
    `);

    // Exam results
    await connection.query(`
      CREATE TABLE IF NOT EXISTS exam_results (
        id INT AUTO_INCREMENT PRIMARY KEY,
        exam_id VARCHAR(20) NOT NULL,
        student_id VARCHAR(20) NOT NULL,
        score DECIMAL(5, 2) NOT NULL,
        max_score INT DEFAULT 100,
        grade VARCHAR(10),
        status ENUM('Completed', 'Absent', 'Pending') DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        UNIQUE KEY unique_result (exam_id, student_id)
      )
    `);

    connection.release();
    console.log('✅ All tables created successfully');
  } catch (error) {
    console.error('Error creating tables:', error);
    process.exit(1);
  }
};

const seedInitialData = async () => {
  try {
    const connection = await pool.getConnection();
    
    // Check if data already exists
    const [students] = await connection.query('SELECT COUNT(*) as count FROM students');
    if (students[0].count > 0) {
      console.log('ℹ️  Database already has data, skipping seed');
      connection.release();
      return;
    }

    // Seed students
    await connection.query(`
      INSERT INTO students (id, first_name, last_name, email, phone, grade, status, enrollment_date, date_of_birth, address, parent_name, parent_email, parent_phone) VALUES
      ('ST001', 'John', 'Smith', 'john.smith@school.edu', '+1 234-567-8900', 'Grade 10', 'Active', '2023-09-01', '2008-05-15', '123 Main Street, New York, NY 10001', 'Jane Smith', 'jane.smith@email.com', '+1 234-567-8901'),
      ('ST002', 'Emily', 'Johnson', 'emily.j@school.edu', '+1 234-567-8901', 'Grade 11', 'Active', '2023-09-01', '2007-03-20', '456 Oak Avenue, New York, NY 10002', 'Robert Johnson', 'robert.j@email.com', '+1 234-567-8902'),
      ('ST003', 'Michael', 'Brown', 'michael.b@school.edu', '+1 234-567-8902', 'Grade 9', 'Active', '2023-09-01', '2009-07-10', '789 Pine Road, New York, NY 10003', 'Sarah Brown', 'sarah.b@email.com', '+1 234-567-8903'),
      ('ST004', 'Sarah', 'Davis', 'sarah.d@school.edu', '+1 234-567-8903', 'Grade 12', 'Active', '2022-09-01', '2006-11-25', '321 Elm Street, New York, NY 10004', 'David Davis', 'david.d@email.com', '+1 234-567-8904'),
      ('ST005', 'David', 'Wilson', 'david.w@school.edu', '+1 234-567-8904', 'Grade 10', 'Inactive', '2023-09-01', '2008-02-14', '654 Maple Drive, New York, NY 10005', 'Lisa Wilson', 'lisa.w@email.com', '+1 234-567-8905'),
      ('ST006', 'Jessica', 'Martinez', 'jessica.m@school.edu', '+1 234-567-8905', 'Grade 11', 'Active', '2023-09-01', '2007-09-30', '987 Cedar Lane, New York, NY 10006', 'Carlos Martinez', 'carlos.m@email.com', '+1 234-567-8906')
    `);

    // Seed teachers
    await connection.query(`
      INSERT INTO teachers (id, first_name, last_name, email, phone, subject, status, join_date, date_of_birth, address, education, experience) VALUES
      ('TC001', 'Robert', 'Anderson', 'robert.a@school.edu', '+1 234-567-9000', 'Mathematics', 'Active', '2020-01-15', '1980-03-20', '456 Faculty Lane, New York, NY 10002', 'Ph.D. in Mathematics, Harvard University', '15 years'),
      ('TC002', 'Maria', 'Garcia', 'maria.g@school.edu', '+1 234-567-9001', 'Science', 'Active', '2019-08-20', '1978-06-15', '789 Teacher Street, New York, NY 10003', 'Ph.D. in Physics, MIT', '18 years'),
      ('TC003', 'Jennifer', 'Lee', 'jennifer.l@school.edu', '+1 234-567-9002', 'English', 'Active', '2021-03-10', '1985-09-05', '321 Educator Avenue, New York, NY 10004', 'M.A. in English Literature, Yale University', '12 years'),
      ('TC004', 'James', 'Taylor', 'james.t@school.edu', '+1 234-567-9003', 'History', 'Active', '2018-09-05', '1975-12-10', '654 Professor Road, New York, NY 10005', 'Ph.D. in History, Columbia University', '20 years'),
      ('TC005', 'Lisa', 'Chen', 'lisa.c@school.edu', '+1 234-567-9004', 'Computer Science', 'Active', '2022-01-10', '1982-04-22', '987 Academic Way, New York, NY 10006', 'Ph.D. in Computer Science, Stanford University', '10 years'),
      ('TC006', 'Thomas', 'White', 'thomas.w@school.edu', '+1 234-567-9005', 'Physical Education', 'On Leave', '2017-06-15', '1970-08-18', '147 Sports Boulevard, New York, NY 10007', 'M.Ed. in Physical Education, UCLA', '25 years')
    `);

    // Seed courses
    await connection.query(`
      INSERT INTO courses (id, name, code, grade, teacher_id, credits, duration, schedule, status) VALUES
      ('CRS001', 'Mathematics - Algebra', 'MATH-101', 'Grade 10', 'TC001', 3, '90 min', 'Mon, Wed, Fri - 9:00 AM', 'Active'),
      ('CRS002', 'Science - Physics', 'SCI-201', 'Grade 11', 'TC002', 4, '90 min', 'Tue, Thu - 10:30 AM', 'Active'),
      ('CRS003', 'English Literature', 'ENG-101', 'Grade 9', 'TC003', 3, '60 min', 'Mon, Wed, Fri - 11:00 AM', 'Active'),
      ('CRS004', 'World History', 'HIS-201', 'Grade 12', 'TC004', 3, '90 min', 'Tue, Thu - 2:00 PM', 'Active'),
      ('CRS005', 'Computer Science', 'CS-301', 'Grade 10', 'TC005', 4, '120 min', 'Mon, Wed - 1:00 PM', 'Active'),
      ('CRS006', 'Physical Education', 'PE-101', 'Grade 11', 'TC006', 2, '60 min', 'Tue, Thu, Fri - 3:00 PM', 'Active')
    `);

    // Seed exams
    await connection.query(`
      INSERT INTO exams (id, title, course_id, grade, exam_date, exam_time, duration, type, status) VALUES
      ('EXM001', 'Midterm Exam - Mathematics', 'CRS001', 'Grade 10', '2024-02-15', '09:00:00', '2 hours', 'Midterm', 'Scheduled'),
      ('EXM002', 'Final Exam - Science', 'CRS002', 'Grade 11', '2024-03-20', '10:00:00', '2 hours', 'Final', 'Scheduled'),
      ('EXM003', 'Quiz - English Literature', 'CRS003', 'Grade 9', '2024-01-25', '11:00:00', '30 min', 'Quiz', 'Completed'),
      ('EXM004', 'Unit Test - World History', 'CRS004', 'Grade 12', '2024-02-10', '14:00:00', '1.5 hours', 'Unit Test', 'Completed'),
      ('EXM005', 'Practical Exam - Computer Science', 'CRS005', 'Grade 10', '2024-02-28', '13:00:00', '2 hours', 'Practical', 'Scheduled'),
      ('EXM006', 'Assessment - Physical Education', 'CRS006', 'Grade 11', '2024-01-30', '15:00:00', '1 hour', 'Assessment', 'In Progress')
    `);

    // Seed payments
    await connection.query(`
      INSERT INTO payments (id, student_id, amount, type, method, payment_date, status, transaction_id) VALUES
      ('PAY001', 'ST001', 1250.00, 'Tuition Fee', 'Credit Card', '2024-01-15', 'Paid', 'TXN-2024-001234'),
      ('PAY002', 'ST002', 1250.00, 'Tuition Fee', 'Bank Transfer', '2024-01-14', 'Paid', 'TXN-2024-001235'),
      ('PAY003', 'ST003', 500.00, 'Library Fee', 'Cash', '2024-01-13', 'Paid', 'TXN-2024-001236'),
      ('PAY004', 'ST004', 1250.00, 'Tuition Fee', 'Credit Card', '2024-01-12', 'Pending', 'TXN-2024-001237'),
      ('PAY005', 'ST005', 300.00, 'Exam Fee', 'Online Payment', '2024-01-11', 'Paid', 'TXN-2024-001238'),
      ('PAY006', 'ST006', 1250.00, 'Tuition Fee', 'Bank Transfer', '2024-01-10', 'Failed', 'TXN-2024-001239')
    `);

    // Seed attendance
    await connection.query(`
      INSERT INTO attendance (id, class_name, teacher_id, attendance_date, total_students, present, absent, late, status) VALUES
      ('ATT001', 'Grade 10 - Mathematics', 'TC001', '2024-01-15', 30, 28, 2, 0, 'Completed'),
      ('ATT002', 'Grade 11 - Science', 'TC002', '2024-01-15', 25, 24, 1, 0, 'Completed'),
      ('ATT003', 'Grade 9 - English', 'TC003', '2024-01-15', 28, 27, 0, 1, 'Completed'),
      ('ATT004', 'Grade 12 - History', 'TC004', '2024-01-14', 22, 20, 2, 0, 'Completed'),
      ('ATT005', 'Grade 10 - Computer Science', 'TC005', '2024-01-14', 30, 29, 1, 0, 'Completed'),
      ('ATT006', 'Grade 11 - Physical Education', 'TC006', '2024-01-16', 25, 0, 0, 0, 'Pending')
    `);

    connection.release();
    console.log('✅ Initial data seeded successfully');
  } catch (error) {
    console.error('Error seeding data:', error);
  }
};

const runMigration = async () => {
  try {
    await createDatabase();
    await createTables();
    await seedInitialData();
    console.log('✅ Migration completed successfully');
    process.exit(0);
  } catch (error) {
    console.error('❌ Migration failed:', error);
    process.exit(1);
  }
};

runMigration();

