<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of students (for web view)
     */
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return $this->indexApi($request);
        }

        $query = Student::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('grade')) {
            $query->where('grade', $request->get('grade'));
        }

        $students = $query->orderBy('created_at', 'desc')->get();

        return view('admin.students.index', [
            'title' => 'Students',
            'userRole' => 'admin',
            'students' => $students
        ]);
    }

    /**
     * Get all students with filters (API)
     */
    public function indexApi(Request $request): JsonResponse
    {
        try {
            $query = Student::query();

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'LIKE', "%{$search}%")
                      ->orWhere('last_name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('id', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status')) {
                $query->where('status', $request->get('status'));
            }

            if ($request->has('grade')) {
                $query->where('grade', $request->get('grade'));
            }

            $students = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching students',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single student by ID with related data
     */
    public function show(Request $request, string $id)
    {
        try {
            $student = Student::find($id);

            if (!$student) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Student not found'
                    ], 404);
                }
                abort(404);
            }

            // Get student's courses
            $courses = DB::table('courses')
                ->join('course_enrollments', 'courses.id', '=', 'course_enrollments.course_id')
                ->where('course_enrollments.student_id', $id)
                ->where('course_enrollments.status', 'Active')
                ->select('courses.*', 'course_enrollments.enrollment_date', DB::raw("course_enrollments.status as enrollment_status"))
                ->get();

            // Get student's exam results
            $examResults = DB::table('exam_results')
                ->join('exams', 'exam_results.exam_id', '=', 'exams.id')
                ->leftJoin('courses', 'exams.course_id', '=', 'courses.id')
                ->where('exam_results.student_id', $id)
                ->select('exam_results.*', 'exams.title', 'exams.exam_date', 'exams.type', DB::raw("courses.name as course_name"))
                ->orderBy('exams.exam_date', 'desc')
                ->get();

            // Get student's payments
            $payments = $student->payments()->orderBy('payment_date', 'desc')->get();

            // Get attendance records
            $attendance = DB::table('attendance')
                ->leftJoin('teachers', 'attendance.teacher_id', '=', 'teachers.id')
                ->where('attendance.class_name', 'LIKE', "%{$student->grade}%")
                ->select('attendance.*', 'teachers.first_name as teacher_first_name', 'teachers.last_name as teacher_last_name')
                ->orderBy('attendance.attendance_date', 'desc')
                ->get();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => array_merge($student->toArray(), [
                        'courses' => $courses,
                        'examResults' => $examResults,
                        'payments' => $payments,
                        'attendance' => $attendance
                    ])
                ]);
            }

            return view('admin.students.show', [
                'title' => 'Student Details',
                'userRole' => 'admin',
                'student' => $student,
                'courses' => $courses,
                'examResults' => $examResults,
                'payments' => $payments,
                'attendance' => $attendance
            ]);
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching student',
                    'error' => $e->getMessage()
                ], 500);
            }
            abort(500);
        }
    }

    /**
     * Create new student
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id' => 'required|string|max:20|unique:students,id',
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => 'required|email|max:255|unique:students,email',
                'phone' => 'nullable|string|max:20',
                'grade' => 'required|string|max:20',
                'status' => 'nullable|in:Active,Inactive',
                'enrollment_date' => 'required|date',
                'date_of_birth' => 'nullable|date',
                'address' => 'nullable|string',
                'parent_name' => 'nullable|string|max:200',
                'parent_email' => 'nullable|email|max:255',
                'parent_phone' => 'nullable|string|max:20',
                'avatar_url' => 'nullable|string|max:500',
            ]);

            $student = Student::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'data' => ['id' => $student->id]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating student',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update student
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $student = Student::find($id);

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            $validated = $request->validate([
                'first_name' => 'sometimes|string|max:100',
                'last_name' => 'sometimes|string|max:100',
                'email' => 'sometimes|email|max:255|unique:students,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'grade' => 'sometimes|string|max:20',
                'status' => 'nullable|in:Active,Inactive',
                'enrollment_date' => 'sometimes|date',
                'date_of_birth' => 'nullable|date',
                'address' => 'nullable|string',
                'parent_name' => 'nullable|string|max:200',
                'parent_email' => 'nullable|email|max:255',
                'parent_phone' => 'nullable|string|max:20',
                'avatar_url' => 'nullable|string|max:500',
            ]);

            $student->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating student',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete student
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $student = Student::find($id);

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            $student->delete();

            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting student',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
