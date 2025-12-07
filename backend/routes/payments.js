import express from 'express';
import pool from '../config/database.js';

const router = express.Router();

// Get all payments
router.get('/', async (req, res) => {
  try {
    const { search, status, type, student_id, start_date, end_date } = req.query;
    let query = `
      SELECT p.*, 
        s.first_name as student_first_name,
        s.last_name as student_last_name,
        s.id as student_id
      FROM payments p
      INNER JOIN students s ON p.student_id = s.id
      WHERE 1=1
    `;
    const params = [];

    if (search) {
      query += ' AND (p.id LIKE ? OR p.transaction_id LIKE ? OR s.first_name LIKE ? OR s.last_name LIKE ?)';
      const searchTerm = `%${search}%`;
      params.push(searchTerm, searchTerm, searchTerm, searchTerm);
    }

    if (status) {
      query += ' AND p.status = ?';
      params.push(status);
    }

    if (type) {
      query += ' AND p.type = ?';
      params.push(type);
    }

    if (student_id) {
      query += ' AND p.student_id = ?';
      params.push(student_id);
    }

    if (start_date) {
      query += ' AND p.payment_date >= ?';
      params.push(start_date);
    }

    if (end_date) {
      query += ' AND p.payment_date <= ?';
      params.push(end_date);
    }

    query += ' ORDER BY p.payment_date DESC, p.created_at DESC';

    const [rows] = await pool.query(query, params);
    res.json({ success: true, data: rows });
  } catch (error) {
    console.error('Error fetching payments:', error);
    res.status(500).json({ success: false, message: 'Error fetching payments', error: error.message });
  }
});

// Get single payment by ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const [rows] = await pool.query(`
      SELECT p.*, 
        s.first_name as student_first_name,
        s.last_name as student_last_name,
        s.email as student_email,
        s.phone as student_phone,
        s.grade as student_grade
      FROM payments p
      INNER JOIN students s ON p.student_id = s.id
      WHERE p.id = ?
    `, [id]);
    
    if (rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Payment not found' });
    }

    res.json({ success: true, data: rows[0] });
  } catch (error) {
    console.error('Error fetching payment:', error);
    res.status(500).json({ success: false, message: 'Error fetching payment', error: error.message });
  }
});

// Create new payment
router.post('/', async (req, res) => {
  try {
    const {
      id, student_id, amount, type, method, payment_date, status, transaction_id, notes
    } = req.body;

    // Check if payment ID already exists
    const [existing] = await pool.query('SELECT id FROM payments WHERE id = ?', [id]);
    if (existing.length > 0) {
      return res.status(400).json({ success: false, message: 'Payment ID already exists' });
    }

    // Check if transaction_id already exists (if provided)
    if (transaction_id) {
      const [txnCheck] = await pool.query('SELECT id FROM payments WHERE transaction_id = ?', [transaction_id]);
      if (txnCheck.length > 0) {
        return res.status(400).json({ success: false, message: 'Transaction ID already exists' });
      }
    }

    // Verify student exists
    const [studentCheck] = await pool.query('SELECT id FROM students WHERE id = ?', [student_id]);
    if (studentCheck.length === 0) {
      return res.status(404).json({ success: false, message: 'Student not found' });
    }

    await pool.query(`
      INSERT INTO payments (
        id, student_id, amount, type, method, payment_date, status, transaction_id, notes
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    `, [id, student_id, amount, type, method, payment_date, status || 'Pending', transaction_id, notes]);

    res.status(201).json({ success: true, message: 'Payment created successfully', data: { id } });
  } catch (error) {
    console.error('Error creating payment:', error);
    res.status(500).json({ success: false, message: 'Error creating payment', error: error.message });
  }
});

// Update payment
router.put('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const {
      student_id, amount, type, method, payment_date, status, transaction_id, notes
    } = req.body;

    // Check if payment exists
    const [existing] = await pool.query('SELECT id FROM payments WHERE id = ?', [id]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, message: 'Payment not found' });
    }

    // Check if transaction_id is being used by another payment
    if (transaction_id) {
      const [txnCheck] = await pool.query('SELECT id FROM payments WHERE transaction_id = ? AND id != ?', [transaction_id, id]);
      if (txnCheck.length > 0) {
        return res.status(400).json({ success: false, message: 'Transaction ID already exists' });
      }
    }

    await pool.query(`
      UPDATE payments SET
        student_id = COALESCE(?, student_id),
        amount = COALESCE(?, amount),
        type = COALESCE(?, type),
        method = COALESCE(?, method),
        payment_date = COALESCE(?, payment_date),
        status = COALESCE(?, status),
        transaction_id = COALESCE(?, transaction_id),
        notes = COALESCE(?, notes)
      WHERE id = ?
    `, [student_id, amount, type, method, payment_date, status, transaction_id, notes, id]);

    res.json({ success: true, message: 'Payment updated successfully' });
  } catch (error) {
    console.error('Error updating payment:', error);
    res.status(500).json({ success: false, message: 'Error updating payment', error: error.message });
  }
});

// Delete payment
router.delete('/:id', async (req, res) => {
  try {
    const { id } = req.params;

    // Check if payment exists
    const [existing] = await pool.query('SELECT id FROM payments WHERE id = ?', [id]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, message: 'Payment not found' });
    }

    await pool.query('DELETE FROM payments WHERE id = ?', [id]);
    res.json({ success: true, message: 'Payment deleted successfully' });
  } catch (error) {
    console.error('Error deleting payment:', error);
    res.status(500).json({ success: false, message: 'Error deleting payment', error: error.message });
  }
});

export default router;

