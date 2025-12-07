import express from 'express';
import pool from '../config/database.js';

const router = express.Router();

// Get all courses
router.get('/', async (req, res) => {
  try {
    const { search, status, grade, teacher_id } = req.query;
    let query = `
      SELECT c.*, 
        t.first_name as teacher_first_name, 
        t.last_name as teacher_last_name,
        (SELECT COUNT(*) FROM course_enrollments WHERE course_id = c.id AND status = 'Active') as student_count
      FROM courses c
      LEFT JOIN teachers t ON c.teacher_id = t.id
      WHERE 1=1
    `;
    const params = [];

    if (search) {
      query += ' AND (c.name LIKE ? OR c.code LIKE ?)';
      const searchTerm = `%${search}%`;
      params.push(searchTerm, searchTerm);
    }

    if (status) {
      query += ' AND c.status = ?';
      params.push(status);
    }

    if (grade) {
      query += ' AND c.grade = ?';
      params.push(grade);
    }

    if (teacher_id) {
      query += ' AND c.teacher_id = ?';
      params.push(teacher_id);
    }

    query += ' ORDER BY c.created_at DESC';

    const [rows] = await pool.query(query, params);
    res.json({ success: true, data: rows });
  } catch (error) {
    console.error('Error fetching courses:', error);
    res.status(500).json({ success: false, message: 'Error fetching courses', error: error.message });
  }
});

// Get single course by ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const [rows] = await pool.query(`
      SELECT c.*, 
        t.first_name as teacher_first_name, 
        t.last_name as teacher_last_name,
        t.email as teacher_email,
        t.phone as teacher_phone
      FROM courses c
      LEFT JOIN teachers t ON c.teacher_id = t.id
      WHERE c.id = ?
    `, [id]);
    
    if (rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Course not found' });
    }

    // Get enrolled students
    const [students] = await pool.query(`
      SELECT s.*, ce.enrollment_date, ce.status as enrollment_status
      FROM students s
      INNER JOIN course_enrollments ce ON s.id = ce.student_id
      WHERE ce.course_id = ? AND ce.status = 'Active'
      ORDER BY s.last_name, s.first_name
    `, [id]);

    // Get course exams
    const [exams] = await pool.query(`
      SELECT * FROM exams
      WHERE course_id = ?
      ORDER BY exam_date DESC
    `, [id]);

    res.json({
      success: true,
      data: {
        ...rows[0],
        students,
        exams
      }
    });
  } catch (error) {
    console.error('Error fetching course:', error);
    res.status(500).json({ success: false, message: 'Error fetching course', error: error.message });
  }
});

// Create new course
router.post('/', async (req, res) => {
  try {
    const {
      id, name, code, grade, teacher_id, credits, duration, schedule, status, description
    } = req.body;

    // Check if course ID already exists
    const [existing] = await pool.query('SELECT id FROM courses WHERE id = ?', [id]);
    if (existing.length > 0) {
      return res.status(400).json({ success: false, message: 'Course ID already exists' });
    }

    // Check if course code already exists
    const [codeCheck] = await pool.query('SELECT id FROM courses WHERE code = ?', [code]);
    if (codeCheck.length > 0) {
      return res.status(400).json({ success: false, message: 'Course code already exists' });
    }

    await pool.query(`
      INSERT INTO courses (
        id, name, code, grade, teacher_id, credits, duration, schedule, status, description
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `, [id, name, code, grade, teacher_id, credits, duration, schedule, status || 'Active', description]);

    res.status(201).json({ success: true, message: 'Course created successfully', data: { id } });
  } catch (error) {
    console.error('Error creating course:', error);
    res.status(500).json({ success: false, message: 'Error creating course', error: error.message });
  }
});

// Update course
router.put('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const {
      name, code, grade, teacher_id, credits, duration, schedule, status, description
    } = req.body;

    // Check if course exists
    const [existing] = await pool.query('SELECT id FROM courses WHERE id = ?', [id]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, message: 'Course not found' });
    }

    // Check if course code is being used by another course
    if (code) {
      const [codeCheck] = await pool.query('SELECT id FROM courses WHERE code = ? AND id != ?', [code, id]);
      if (codeCheck.length > 0) {
        return res.status(400).json({ success: false, message: 'Course code already exists' });
      }
    }

    await pool.query(`
      UPDATE courses SET
        name = COALESCE(?, name),
        code = COALESCE(?, code),
        grade = COALESCE(?, grade),
        teacher_id = COALESCE(?, teacher_id),
        credits = COALESCE(?, credits),
        duration = COALESCE(?, duration),
        schedule = COALESCE(?, schedule),
        status = COALESCE(?, status),
        description = COALESCE(?, description)
      WHERE id = ?
    `, [name, code, grade, teacher_id, credits, duration, schedule, status, description, id]);

    res.json({ success: true, message: 'Course updated successfully' });
  } catch (error) {
    console.error('Error updating course:', error);
    res.status(500).json({ success: false, message: 'Error updating course', error: error.message });
  }
});

// Delete course
router.delete('/:id', async (req, res) => {
  try {
    const { id } = req.params;

    // Check if course exists
    const [existing] = await pool.query('SELECT id FROM courses WHERE id = ?', [id]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, message: 'Course not found' });
    }

    await pool.query('DELETE FROM courses WHERE id = ?', [id]);
    res.json({ success: true, message: 'Course deleted successfully' });
  } catch (error) {
    console.error('Error deleting course:', error);
    res.status(500).json({ success: false, message: 'Error deleting course', error: error.message });
  }
});

export default router;

