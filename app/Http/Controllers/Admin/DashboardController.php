<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Exam;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getStatsData();
        return view('admin.dashboard', [
            'title' => 'Dashboard',
            'userRole' => 'admin',
            'dashboardData' => $stats
        ]);
    }

    private function getStatsData()
    {
        try {
            // Total students
            $totalStudents = Student::where('status', 'Active')->count();

            // Total teachers
            $totalTeachers = Teacher::where('status', 'Active')->count();

            // Total courses
            $totalCourses = Course::where('status', 'Active')->count();

            // Total payments this month
            $currentMonth = now()->format('Y-m');
            $totalPayments = Payment::where('status', 'Paid')
                ->whereRaw("DATE_FORMAT(payment_date, '%Y-%m') = ?", [$currentMonth])
                ->sum('amount');

            // Total parents (unique parent emails)
            $totalParents = Student::where('status', 'Active')
                ->whereNotNull('parent_email')
                ->where('parent_email', '!=', '')
                ->distinct('parent_email')
                ->count('parent_email');

            // Earnings data for chart (last 12 months)
            $earningsData = Payment::selectRaw("
                    DATE_FORMAT(payment_date, '%Y-%m') as month,
                    DATE_FORMAT(payment_date, '%b') as month_name,
                    SUM(CASE WHEN status = 'Paid' THEN amount ELSE 0 END) as earnings,
                    SUM(CASE WHEN status = 'Refunded' THEN amount ELSE 0 END) as expenses
                ")
                ->where('payment_date', '>=', now()->subMonths(12))
                ->groupBy(DB::raw("DATE_FORMAT(payment_date, '%Y-%m')"), DB::raw("DATE_FORMAT(payment_date, '%b')"))
                ->orderBy('month')
                ->get();

            // Top performers (students with best exam results)
            $topPerformers = DB::table('students')
                ->join('exam_results', 'students.id', '=', 'exam_results.student_id')
                ->where('exam_results.status', 'Completed')
                ->where('students.status', 'Active')
                ->selectRaw('
                    students.id,
                    students.first_name,
                    students.last_name,
                    students.grade,
                    AVG(exam_results.score / exam_results.max_score * 100) as avg_score,
                    COUNT(exam_results.id) as exam_count
                ')
                ->groupBy('students.id', 'students.first_name', 'students.last_name', 'students.grade')
                ->havingRaw('exam_count >= 2')
                ->orderBy('avg_score', 'desc')
                ->limit(10)
                ->get();

            // Recent students
            $recentStudents = Student::orderBy('created_at', 'desc')->limit(5)->get();

            // Recent payments
            $recentPayments = Payment::with('student')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'student_id' => $payment->student_id,
                        'amount' => $payment->amount,
                        'type' => $payment->type,
                        'method' => $payment->method,
                        'payment_date' => $payment->payment_date,
                        'status' => $payment->status,
                        'transaction_id' => $payment->transaction_id,
                        'created_at' => $payment->created_at,
                        'first_name' => $payment->student ? $payment->student->first_name : null,
                        'last_name' => $payment->student ? $payment->student->last_name : null,
                    ];
                });

            // Upcoming exams
            $upcomingExams = Exam::with('course')
                ->where('exam_date', '>=', now()->toDateString())
                ->where('status', 'Scheduled')
                ->orderBy('exam_date', 'asc')
                ->limit(5)
                ->get()
                ->map(function ($exam) {
                    return [
                        'id' => $exam->id,
                        'title' => $exam->title,
                        'course_id' => $exam->course_id,
                        'grade' => $exam->grade,
                        'exam_date' => $exam->exam_date,
                        'exam_time' => $exam->exam_time,
                        'duration' => $exam->duration,
                        'type' => $exam->type,
                        'status' => $exam->status,
                        'max_score' => $exam->max_score,
                        'course_name' => $exam->course ? $exam->course->name : null,
                    ];
                });

            // Attendance summary (last 7 days)
            $attendanceSummary = Attendance::selectRaw('
                    DATE(attendance_date) as date,
                    SUM(total_students) as total,
                    SUM(present) as present,
                    SUM(absent) as absent,
                    SUM(late) as late
                ')
                ->where('attendance_date', '>=', now()->subDays(7))
                ->groupBy(DB::raw('DATE(attendance_date)'))
                ->orderBy('date', 'desc')
                ->get();

            return [
                'stats' => [
                    'totalStudents' => $totalStudents,
                    'totalTeachers' => $totalTeachers,
                    'totalCourses' => $totalCourses,
                    'totalParents' => $totalParents,
                    'totalPayments' => (float) $totalPayments,
                ],
                'recentStudents' => $recentStudents,
                'recentPayments' => $recentPayments,
                'upcomingExams' => $upcomingExams,
                'attendanceSummary' => $attendanceSummary,
                'earningsData' => $earningsData,
                'topPerformers' => $topPerformers,
            ];
        } catch (\Exception $e) {
            return [
                'stats' => [
                    'totalStudents' => 0,
                    'totalTeachers' => 0,
                    'totalCourses' => 0,
                    'totalParents' => 0,
                    'totalPayments' => 0,
                ],
                'recentStudents' => [],
                'recentPayments' => [],
                'upcomingExams' => [],
                'attendanceSummary' => [],
                'earningsData' => [],
                'topPerformers' => [],
            ];
        }
    }

    public function stats(): JsonResponse
    {
        try {
            $data = $this->getStatsData();
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching dashboard stats',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

