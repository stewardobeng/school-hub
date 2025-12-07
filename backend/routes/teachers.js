import express from 'express';
import pool from '../config/database.js';

const router = express.Router();

// Get all teachers
router.get('/', async (req, res) => {
  try {
    const { search, status, subject } = req.query;
    let query = 'SELECT * FROM teachers WHERE 1=1';
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

    if (subject) {
      query += ' AND subject = ?';
      params.push(subject);
    }

    query += ' ORDER BY created_at DESC';

    const [rows] = await pool.query(query, params);
    res.json({ success: true, data: rows });
  } catch (error) {
    console.error('Error fetching teachers:', error);
    res.status(500).json({ success: false, message: 'Error fetching teachers', error: error.message });
  }
});

// Get single teacher by ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const [rows] = await pool.query('SELECT * FROM teachers WHERE id = ?', [id]);
    
    if (rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Teacher not found' });
    }

    // Get teacher's courses
    const [courses] = await pool.query(`
      SELECT c.*, 
        (SELECT COUNT(*) FROM course_enrollments WHERE course_id = c.id AND status = 'Active') as student_count
      FROM courses c
      WHERE c.teacher_id = ? AND c.status = 'Active'
    `, [id]);

    // Get teacher's classes schedule
    const [schedule] = await pool.query(`
      SELECT DISTINCT schedule, name, code, grade
      FROM courses
      WHERE teacher_id = ? AND status = 'Active'
      ORDER BY schedule
    `, [id]);

    // Get teacher's students (through courses)
    const [students] = await pool.query(`
      SELECT DISTINCT s.*
      FROM students s
      INNER JOIN course_enrollments ce ON s.id = ce.student_id
      INNER JOIN courses c ON ce.course_id = c.id
      WHERE c.teacher_id = ? AND ce.status = 'Active'
      ORDER BY s.last_name, s.first_name
    `, [id]);

    // Get attendance records
    const [attendance] = await pool.query(`
      SELECT * FROM attendance
      WHERE teacher_id = ?
      ORDER BY attendance_date DESC
      LIMIT 10
    `, [id]);

    res.json({
      success: true,
      data: {
        ...rows[0],
        courses,
        schedule,
        students,
        attendance
      }
    });
  } catch (error) {
    console.error('Error fetching teacher:', error);
    res.status(500).json({ success: false, message: 'Error fetching teacher', error: error.message });
  }
});

// Create new teacher
router.post('/', async (req, res) => {
  try {
    const {
      id, first_name, last_name, email, phone, subject, status, join_date,
      date_of_birth, address, education, experience, avatar_url
    } = req.body;

    // Check if teacher ID already exists
    const [existing] = await pool.query('SELECT id FROM teachers WHERE id = ?', [id]);
    if (existing.length > 0) {
      return res.status(400).json({ success: false, message: 'Teacher ID already exists' });
    }

    // Check if email already exists
    const [emailCheck] = await pool.query('SELECT id FROM teachers WHERE email = ?', [email]);
    if (emailCheck.length > 0) {
      return res.status(400).json({ success: false, message: 'Email already exists' });
    }

    await pool.query(`
      INSERT INTO teachers (
        id, first_name, last_name, email, phone, subject, status, join_date,
        date_of_birth, address, education, experience, avatar_url
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `, [id, first_name, last_name, email, phone, subject, status || 'Active', join_date,
        date_of_birth, address, education, experience, avatar_url]);

    res.status(201).json({ success: true, message: 'Teacher created successfully', data: { id } });
  } catch (error) {
    console.error('Error creating teacher:', error);
    res.status(500).json({ success: false, message: 'Error creating teacher', error: error.message });
  }
});

// Update teacher
router.put('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const {
      first_name, last_name, email, phone, subject, status, join_date,
      date_of_birth, address, education, experience, avatar_url
    } = req.body;

    // Check if teacher exists
    const [existing] = await pool.query('SELECT id FROM teachers WHERE id = ?', [id]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, message: 'Teacher not found' });
    }

    // Check if email is being used by another teacher
    if (email) {
      const [emailCheck] = await pool.query('SELECT id FROM teachers WHERE email = ? AND id != ?', [email, id]);
      if (emailCheck.length > 0) {
        return res.status(400).json({ success: false, message: 'Email already exists' });
      }
    }

    await pool.query(`
      UPDATE teachers SET
        first_name = COALESCE(?, first_name),
        last_name = COALESCE(?, last_name),
        email = COALESCE(?, email),
        phone = COALESCE(?, phone),
        subject = COALESCE(?, subject),
        status = COALESCE(?, status),
        join_date = COALESCE(?, join_date),
        date_of_birth = COALESCE(?, date_of_birth),
        address = COALESCE(?, address),
        education = COALESCE(?, education),
        experience = COALESCE(?, experience),
        avatar_url = COALESCE(?, avatar_url)
      WHERE id = ?
    `, [first_name, last_name, email, phone, subject, status, join_date,
        date_of_birth, address, education, experience, avatar_url, id]);

    res.json({ success: true, message: 'Teacher updated successfully' });
  } catch (error) {
    console.error('Error updating teacher:', error);
    res.status(500).json({ success: false, message: 'Error updating teacher', error: error.message });
  }
});

// Delete teacher
router.delete('/:id', async (req, res) => {
  try {
    const { id } = req.params;

    // Check if teacher exists
    const [existing] = await pool.query('SELECT id FROM teachers WHERE id = ?', [id]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, message: 'Teacher not found' });
    }

    await pool.query('DELETE FROM teachers WHERE id = ?', [id]);
    res.json({ success: true, message: 'Teacher deleted successfully' });
  } catch (error) {
    console.error('Error deleting teacher:', error);
    res.status(500).json({ success: false, message: 'Error deleting teacher', error: error.message });
  }
});

export default router;

