import express from 'express';
import pool from '../config/database.js';

const router = express.Router();

// Get all exams
router.get('/', async (req, res) => {
  try {
    const { search, status, type, course_id, grade } = req.query;
    let query = `
      SELECT e.*, 
        c.name as course_name,
        c.code as course_code,
        t.first_name as teacher_first_name,
        t.last_name as teacher_last_name
      FROM exams e
      LEFT JOIN courses c ON e.course_id = c.id
      LEFT JOIN teachers t ON c.teacher_id = t.id
      WHERE 1=1
    `;
    const params = [];

    if (search) {
      query += ' AND (e.title LIKE ? OR e.id LIKE ?)';
      const searchTerm = `%${search}%`;
      params.push(searchTerm, searchTerm);
    }

    if (status) {
      query += ' AND e.status = ?';
      params.push(status);
    }

    if (type) {
      query += ' AND e.type = ?';
      params.push(type);
    }

    if (course_id) {
      query += ' AND e.course_id = ?';
      params.push(course_id);
    }

    if (grade) {
      query += ' AND e.grade = ?';
      params.push(grade);
    }

    query += ' ORDER BY e.exam_date DESC, e.exam_time DESC';

    const [rows] = await pool.query(query, params);
    res.json({ success: true, data: rows });
  } catch (error) {
    console.error('Error fetching exams:', error);
    res.status(500).json({ success: false, message: 'Error fetching exams', error: error.message });
  }
});

// Get single exam by ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const [rows] = await pool.query(`
      SELECT e.*, 
        c.name as course_name,
        c.code as course_code,
        t.first_name as teacher_first_name,
        t.last_name as teacher_last_name
      FROM exams e
      LEFT JOIN courses c ON e.course_id = c.id
      LEFT JOIN teachers t ON c.teacher_id = t.id
      WHERE e.id = ?
    `, [id]);
    
    if (rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Exam not found' });
    }

    // Get exam results
    const [results] = await pool.query(`
      SELECT er.*, 
        s.first_name as student_first_name,
        s.last_name as student_last_name,
        s.id as student_id
      FROM exam_results er
      INNER JOIN students s ON er.student_id = s.id
      WHERE er.exam_id = ?
      ORDER BY s.last_name, s.first_name
    `, [id]);

    res.json({
      success: true,
      data: {
        ...rows[0],
        results
      }
    });
  } catch (error) {
    console.error('Error fetching exam:', error);
    res.status(500).json({ success: false, message: 'Error fetching exam', error: error.message });
  }
});

// Create new exam
router.post('/', async (req, res) => {
  try {
    const {
      id, title, course_id, grade, exam_date, exam_time, duration, type, status, max_score
    } = req.body;

    // Check if exam ID already exists
    const [existing] = await pool.query('SELECT id FROM exams WHERE id = ?', [id]);
    if (existing.length > 0) {
      return res.status(400).json({ success: false, message: 'Exam ID already exists' });
    }

    await pool.query(`
      INSERT INTO exams (
        id, title, course_id, grade, exam_date, exam_time, duration, type, status, max_score
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `, [id, title, course_id, grade, exam_date, exam_time, duration, type, status || 'Scheduled', max_score || 100]);

    res.status(201).json({ success: true, message: 'Exam created successfully', data: { id } });
  } catch (error) {
    console.error('Error creating exam:', error);
    res.status(500).json({ success: false, message: 'Error creating exam', error: error.message });
  }
});

// Update exam
router.put('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const {
      title, course_id, grade, exam_date, exam_time, duration, type, status, max_score
    } = req.body;

    // Check if exam exists
    const [existing] = await pool.query('SELECT id FROM exams WHERE id = ?', [id]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, message: 'Exam not found' });
    }

    await pool.query(`
      UPDATE exams SET
        title = COALESCE(?, title),
        course_id = COALESCE(?, course_id),
        grade = COALESCE(?, grade),
        exam_date = COALESCE(?, exam_date),
        exam_time = COALESCE(?, exam_time),
        duration = COALESCE(?, duration),
        type = COALESCE(?, type),
        status = COALESCE(?, status),
        max_score = COALESCE(?, max_score)
      WHERE id = ?
    `, [title, course_id, grade, exam_date, exam_time, duration, type, status, max_score, id]);

    res.json({ success: true, message: 'Exam updated successfully' });
  } catch (error) {
    console.error('Error updating exam:', error);
    res.status(500).json({ success: false, message: 'Error updating exam', error: error.message });
  }
});

// Delete exam
router.delete('/:id', async (req, res) => {
  try {
    const { id } = req.params;

    // Check if exam exists
    const [existing] = await pool.query('SELECT id FROM exams WHERE id = ?', [id]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, message: 'Exam not found' });
    }

    await pool.query('DELETE FROM exams WHERE id = ?', [id]);
    res.json({ success: true, message: 'Exam deleted successfully' });
  } catch (error) {
    console.error('Error deleting exam:', error);
    res.status(500).json({ success: false, message: 'Error deleting exam', error: error.message });
  }
});

export default router;

