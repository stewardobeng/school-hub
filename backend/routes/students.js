import express from 'express';
import pool from '../config/database.js';

const router = express.Router();

// Get all students
router.get('/', async (req, res) => {
  try {
    const { search, status, grade } = req.query;
    let query = 'SELECT * FROM students WHERE 1=1';
    const params = [];

    if (search) {
      query += ' AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR id LIKE ?)';
      const searchTerm = `%${search}%`;
      params.push(searchTerm, searchTerm, searchTerm, searchTerm);
    }

    if (status) {
      query += ' AND status = ?';
      params.push(status);
    }

    if (grade) {
      query += ' AND grade = ?';
      params.push(grade);
    }

    query += ' ORDER BY created_at DESC';

    const [rows] = await pool.query(query, params);
    res.json({ success: true, data: rows });
  } catch (error) {
    console.error('Error fetching students:', error);
    res.status(500).json({ success: false, message: 'Error fetching students', error: error.message });
  }
});

// Get single student by ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const [rows] = await pool.query('SELECT * FROM students WHERE id = ?', [id]);
    
    if (rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Student not found' });
    }

    // Get student's courses
    const [courses] = await pool.query(`
      SELECT c.*, ce.enrollment_date, ce.status as enrollment_status
      FROM courses c
      INNER JOIN course_enrollments ce ON c.id = ce.course_id
      WHERE ce.student_id = ? AND ce.status = 'Active'
    `, [id]);

    // Get student's exam results
    const [examResults] = await pool.query(`
      SELECT er.*, e.title, e.exam_date, e.type, c.name as course_name
      FROM exam_results er
      INNER JOIN exams e ON er.exam_id = e.id
      LEFT JOIN courses c ON e.course_id = c.id
      WHERE er.student_id = ?
      ORDER BY e.exam_date DESC
    `, [id]);

    // Get student's payments
    const [payments] = await pool.query(`
      SELECT * FROM payments
      WHERE student_id = ?
      ORDER BY payment_date DESC
    `, [id]);

    // Get attendance records
    const [attendance] = await pool.query(`
      SELECT a.*, t.first_name as teacher_first_name, t.last_name as teacher_last_name
      FROM attendance a
      LEFT JOIN teachers t ON a.teacher_id = t.id
      WHERE a.class_name LIKE ?
      ORDER BY a.attendance_date DESC
    `, [`%${rows[0].grade}%`]);

    res.json({
      success: true,
      data: {
        ...rows[0],
        courses,
        examResults,
        payments,
        attendance
      }
    });
  } catch (error) {
    console.error('Error fetching student:', error);
    res.status(500).json({ success: false, message: 'Error fetching student', error: error.message });
  }
});

// Create new student
router.post('/', async (req, res) => {
  try {
    const {
      id, first_name, last_name, email, phone, grade, status, enrollment_date,
      date_of_birth, address, parent_name, parent_email, parent_phone, avatar_url
    } = req.body;

    // Check if student ID already exists
    const [existing] = await pool.query('SELECT id FROM students WHERE id = ?', [id]);
    if (existing.length > 0) {
      return res.status(400).json({ success: false, message: 'Student ID already exists' });
    }

    // Check if email already exists
    const [emailCheck] = await pool.query('SELECT id FROM students WHERE email = ?', [email]);
    if (emailCheck.length > 0) {
      return res.status(400).json({ success: false, message: 'Email already exists' });
    }

    await pool.query(`
      INSERT INTO students (
        id, first_name, last_name, email, phone, grade, status, enrollment_date,
        date_of_birth, address, parent_name, parent_email, parent_phone, avatar_url
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `, [id, first_name, last_name, email, phone, grade, status || 'Active', enrollment_date,
        date_of_birth, address, parent_name, parent_email, parent_phone, avatar_url]);

    res.status(201).json({ success: true, message: 'Student created successfully', data: { id } });
  } catch (error) {
    console.error('Error creating student:', error);
    res.status(500).json({ success: false, message: 'Error creating student', error: error.message });
  }
});

// Update student
router.put('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const {
      first_name, last_name, email, phone, grade, status, enrollment_date,
      date_of_birth, address, parent_name, parent_email, parent_phone, avatar_url
    } = req.body;

    // Check if student exists
    const [existing] = await pool.query('SELECT id FROM students WHERE id = ?', [id]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, message: 'Student not found' });
    }

    // Check if email is being used by another student
    if (email) {
      const [emailCheck] = await pool.query('SELECT id FROM students WHERE email = ? AND id != ?', [email, id]);
      if (emailCheck.length > 0) {
        return res.status(400).json({ success: false, message: 'Email already exists' });
      }
    }

    await pool.query(`
      UPDATE students SET
        first_name = COALESCE(?, first_name),
        last_name = COALESCE(?, last_name),
        email = COALESCE(?, email),
        phone = COALESCE(?, phone),
        grade = COALESCE(?, grade),
        status = COALESCE(?, status),
        enrollment_date = COALESCE(?, enrollment_date),
        date_of_birth = COALESCE(?, date_of_birth),
        address = COALESCE(?, address),
        parent_name = COALESCE(?, parent_name),
        parent_email = COALESCE(?, parent_email),
        parent_phone = COALESCE(?, parent_phone),
        avatar_url = COALESCE(?, avatar_url)
      WHERE id = ?
    `, [first_name, last_name, email, phone, grade, status, enrollment_date,
        date_of_birth, address, parent_name, parent_email, parent_phone, avatar_url, id]);

    res.json({ success: true, message: 'Student updated successfully' });
  } catch (error) {
    console.error('Error updating student:', error);
    res.status(500).json({ success: false, message: 'Error updating student', error: error.message });
  }
});

// Delete student
router.delete('/:id', async (req, res) => {
  try {
    const { id } = req.params;

    // Check if student exists
    const [existing] = await pool.query('SELECT id FROM students WHERE id = ?', [id]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, message: 'Student not found' });
    }

    await pool.query('DELETE FROM students WHERE id = ?', [id]);
    res.json({ success: true, message: 'Student deleted successfully' });
  } catch (error) {
    console.error('Error deleting student:', error);
    res.status(500).json({ success: false, message: 'Error deleting student', error: error.message });
  }
});

export default router;

