import express from 'express';
import pool from '../config/database.js';

const router = express.Router();

// Get dashboard statistics
router.get('/stats', async (req, res) => {
  try {
    // Total students
    const [studentsResult] = await pool.query('SELECT COUNT(*) as total FROM students WHERE status = "Active"');
    const totalStudents = studentsResult[0].total;

    // Total teachers
    const [teachersResult] = await pool.query('SELECT COUNT(*) as total FROM teachers WHERE status = "Active"');
    const totalTeachers = teachersResult[0].total;

    // Total courses
    const [coursesResult] = await pool.query('SELECT COUNT(*) as total FROM courses WHERE status = "Active"');
    const totalCourses = coursesResult[0].total;

    // Total payments this month
    const currentMonth = new Date().toISOString().slice(0, 7); // YYYY-MM
    const [paymentsResult] = await pool.query(`
      SELECT SUM(amount) as total 
      FROM payments 
      WHERE status = 'Paid' AND DATE_FORMAT(payment_date, '%Y-%m') = ?
    `, [currentMonth]);
    const totalPayments = paymentsResult[0].total || 0;

    // Total parents (unique parent emails from students)
    const [parentsResult] = await pool.query(`
      SELECT COUNT(DISTINCT COALESCE(parent_email, '')) as total 
      FROM students 
      WHERE parent_email IS NOT NULL AND parent_email != '' AND status = 'Active'
    `);
    const totalParents = parentsResult[0]?.total || 0;

    // Earnings data for chart (last 12 months)
    const [earningsData] = await pool.query(`
      SELECT 
        DATE_FORMAT(payment_date, '%Y-%m') as month,
        DATE_FORMAT(payment_date, '%b') as month_name,
        SUM(CASE WHEN status = 'Paid' THEN amount ELSE 0 END) as earnings,
        SUM(CASE WHEN status = 'Refunded' THEN amount ELSE 0 END) as expenses
      FROM payments
      WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
      GROUP BY DATE_FORMAT(payment_date, '%Y-%m'), DATE_FORMAT(payment_date, '%b')
      ORDER BY month ASC
    `);

    // Top performers (students with best exam results)
    const [topPerformers] = await pool.query(`
      SELECT 
        s.id,
        s.first_name,
        s.last_name,
        s.grade,
        AVG(er.score / er.max_score * 100) as avg_score,
        COUNT(er.id) as exam_count
      FROM students s
      INNER JOIN exam_results er ON s.id = er.student_id
      WHERE er.status = 'Completed' AND s.status = 'Active'
      GROUP BY s.id, s.first_name, s.last_name, s.grade
      HAVING exam_count >= 2
      ORDER BY avg_score DESC
      LIMIT 10
    `);

    // Recent students
    const [recentStudents] = await pool.query(`
      SELECT * FROM students 
      ORDER BY created_at DESC 
      LIMIT 5
    `);

    // Recent payments
    const [recentPayments] = await pool.query(`
      SELECT p.*, s.first_name, s.last_name 
      FROM payments p
      INNER JOIN students s ON p.student_id = s.id
      ORDER BY p.created_at DESC 
      LIMIT 5
    `);

    // Upcoming exams
    const [upcomingExams] = await pool.query(`
      SELECT e.*, c.name as course_name
      FROM exams e
      LEFT JOIN courses c ON e.course_id = c.id
      WHERE e.exam_date >= CURDATE() AND e.status = 'Scheduled'
      ORDER BY e.exam_date ASC
      LIMIT 5
    `);

    // Attendance summary (last 7 days)
    const [attendanceSummary] = await pool.query(`
      SELECT 
        DATE(attendance_date) as date,
        SUM(total_students) as total,
        SUM(present) as present,
        SUM(absent) as absent,
        SUM(late) as late
      FROM attendance
      WHERE attendance_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
      GROUP BY DATE(attendance_date)
      ORDER BY date DESC
    `);

    res.json({
      success: true,
      data: {
        stats: {
          totalStudents,
          totalTeachers,
          totalCourses,
          totalParents,
          totalPayments: parseFloat(totalPayments)
        },
        recentStudents,
        recentPayments,
        upcomingExams,
        attendanceSummary,
        earningsData,
        topPerformers
      }
    });
  } catch (error) {
    console.error('Error fetching dashboard stats:', error);
    res.status(500).json({ success: false, message: 'Error fetching dashboard stats', error: error.message });
  }
});

export default router;

