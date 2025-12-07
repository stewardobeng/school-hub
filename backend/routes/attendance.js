import express from 'express';
import pool from '../config/database.js';

const router = express.Router();

// Get all attendance records
router.get('/', async (req, res) => {
  try {
    const { search, status, teacher_id, start_date, end_date, class_name } = req.query;
    let query = `
      SELECT a.*, 
        t.first_name as teacher_first_name,
        t.last_name as teacher_last_name
      FROM attendance a
      LEFT JOIN teachers t ON a.teacher_id = t.id
      WHERE 1=1
    `;
    const params = [];

    if (search) {
      query += ' AND (a.class_name LIKE ? OR a.id LIKE ?)';
      const searchTerm = `%${search}%`;
      params.push(searchTerm, searchTerm);
    }

    if (status) {
      query += ' AND a.status = ?';
      params.push(status);
    }

    if (teacher_id) {
      query += ' AND a.teacher_id = ?';
      params.push(teacher_id);
    }

    if (class_name) {
      query += ' AND a.class_name LIKE ?';
      params.push(`%${class_name}%`);
    }

    if (start_date) {
      query += ' AND a.attendance_date >= ?';
      params.push(start_date);
    }

    if (end_date) {
      query += ' AND a.attendance_date <= ?';
      params.push(end_date);
    }

    query += ' ORDER BY a.attendance_date DESC, a.created_at DESC';

    const [rows] = await pool.query(query, params);
    res.json({ success: true, data: rows });
  } catch (error) {
    console.error('Error fetching attendance:', error);
    res.status(500).json({ success: false, message: 'Error fetching attendance', error: error.message });
  }
});

// Get single attendance record by ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const [rows] = await pool.query(`
      SELECT a.*, 
        t.first_name as teacher_first_name,
        t.last_name as teacher_last_name,
        t.email as teacher_email,
        t.phone as teacher_phone
      FROM attendance a
      LEFT JOIN teachers t ON a.teacher_id = t.id
      WHERE a.id = ?
    `, [id]);
    
    if (rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Attendance record not found' });
    }

    res.json({ success: true, data: rows[0] });
  } catch (error) {
    console.error('Error fetching attendance:', error);
    res.status(500).json({ success: false, message: 'Error fetching attendance', error: error.message });
  }
});

// Create new attendance record
router.post('/', async (req, res) => {
  try {
    const {
      id, class_name, teacher_id, attendance_date, total_students, present, absent, late, status, notes
    } = req.body;

    // Check if attendance ID already exists
    const [existing] = await pool.query('SELECT id FROM attendance WHERE id = ?', [id]);
    if (existing.length > 0) {
      return res.status(400).json({ success: false, message: 'Attendance ID already exists' });
    }

    // Validate numbers
    if (present + absent + late !== total_students) {
      return res.status(400).json({ 
        success: false, 
        message: 'Present + Absent + Late must equal Total Students' 
      });
    }

    await pool.query(`
      INSERT INTO attendance (
        id, class_name, teacher_id, attendance_date, total_students, present, absent, late, status, notes
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `, [id, class_name, teacher_id, attendance_date, total_students, present, absent, late, status || 'Pending', notes]);

    res.status(201).json({ success: true, message: 'Attendance record created successfully', data: { id } });
  } catch (error) {
    console.error('Error creating attendance:', error);
    res.status(500).json({ success: false, message: 'Error creating attendance', error: error.message });
  }
});

// Update attendance record
router.put('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const {
      class_name, teacher_id, attendance_date, total_students, present, absent, late, status, notes
    } = req.body;

    // Check if attendance exists
    const [existing] = await pool.query('SELECT id FROM attendance WHERE id = ?', [id]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, message: 'Attendance record not found' });
    }

    // Validate numbers if all are provided
    if (present !== undefined && absent !== undefined && late !== undefined && total_students !== undefined) {
      if (present + absent + late !== total_students) {
        return res.status(400).json({ 
          success: false, 
          message: 'Present + Absent + Late must equal Total Students' 
        });
      }
    }

    await pool.query(`
      UPDATE attendance SET
        class_name = COALESCE(?, class_name),
        teacher_id = COALESCE(?, teacher_id),
        attendance_date = COALESCE(?, attendance_date),
        total_students = COALESCE(?, total_students),
        present = COALESCE(?, present),
        absent = COALESCE(?, absent),
        late = COALESCE(?, late),
        status = COALESCE(?, status),
        notes = COALESCE(?, notes)
      WHERE id = ?
    `, [class_name, teacher_id, attendance_date, total_students, present, absent, late, status, notes, id]);

    res.json({ success: true, message: 'Attendance record updated successfully' });
  } catch (error) {
    console.error('Error updating attendance:', error);
    res.status(500).json({ success: false, message: 'Error updating attendance', error: error.message });
  }
});

// Delete attendance record
router.delete('/:id', async (req, res) => {
  try {
    const { id } = req.params;

    // Check if attendance exists
    const [existing] = await pool.query('SELECT id FROM attendance WHERE id = ?', [id]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, message: 'Attendance record not found' });
    }

    await pool.query('DELETE FROM attendance WHERE id = ?', [id]);
    res.json({ success: true, message: 'Attendance record deleted successfully' });
  } catch (error) {
    console.error('Error deleting attendance:', error);
    res.status(500).json({ success: false, message: 'Error deleting attendance', error: error.message });
  }
});

export default router;

