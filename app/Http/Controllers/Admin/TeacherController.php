<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return $this->indexApi($request);
        }

        try {
            $query = Teacher::query();

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

            if ($request->has('subject')) {
                $query->where('subject', $request->get('subject'));
            }

            $teachers = $query->orderBy('created_at', 'desc')->get();

            return view('admin.teachers.index', [
                'title' => 'Teachers',
                'userRole' => 'admin',
                'teachers' => $teachers
            ]);
        } catch (\Exception $e) {
            return view('admin.teachers.index', [
                'title' => 'Teachers',
                'userRole' => 'admin',
                'teachers' => collect([])
            ]);
        }
    }

    private function indexApi(Request $request): JsonResponse
    {
        try {
            $query = Teacher::query();

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

            if ($request->has('subject')) {
                $query->where('subject', $request->get('subject'));
            }

            $teachers = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $teachers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching teachers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, string $id)
    {
        try {
            $teacher = Teacher::find($id);

            if (!$teacher) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Teacher not found'
                    ], 404);
                }
                abort(404);
            }

            // Get teacher's courses
            $courses = DB::table('courses')
                ->where('teacher_id', $id)
                ->where('status', 'Active')
                ->select('courses.*', DB::raw("(SELECT COUNT(*) FROM course_enrollments WHERE course_id = courses.id AND status = 'Active') as student_count"))
                ->get();

            // Get teacher's schedule
            $schedule = DB::table('courses')
                ->where('teacher_id', $id)
                ->where('status', 'Active')
                ->select('schedule', 'name', 'code', 'grade')
                ->distinct()
                ->orderBy('schedule')
                ->get();

            // Get teacher's students
            $students = DB::table('students')
                ->join('course_enrollments', 'students.id', '=', 'course_enrollments.student_id')
                ->join('courses', 'course_enrollments.course_id', '=', 'courses.id')
                ->where('courses.teacher_id', $id)
                ->where('course_enrollments.status', 'Active')
                ->select('students.*')
                ->distinct()
                ->orderBy('students.last_name')
                ->orderBy('students.first_name')
                ->get();

            // Get attendance records
            $attendance = $teacher->attendanceRecords()->orderBy('attendance_date', 'desc')->limit(10)->get();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => array_merge($teacher->toArray(), [
                        'courses' => $courses,
                        'schedule' => $schedule,
                        'students' => $students,
                        'attendance' => $attendance
                    ])
                ]);
            }

            return view('admin.teachers.show', [
                'title' => 'Teacher Details',
                'userRole' => 'admin',
                'teacher' => $teacher,
                'courses' => $courses,
                'schedule' => $schedule,
                'students' => $students,
                'attendance' => $attendance
            ]);
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching teacher',
                    'error' => $e->getMessage()
                ], 500);
            }
            abort(500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id' => 'required|string|max:20|unique:teachers,id',
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => 'required|email|max:255|unique:teachers,email',
                'phone' => 'nullable|string|max:20',
                'subject' => 'required|string|max:100',
                'status' => 'nullable|in:Active,On Leave,Inactive',
                'join_date' => 'required|date',
                'date_of_birth' => 'nullable|date',
                'address' => 'nullable|string',
                'education' => 'nullable|string',
                'experience' => 'nullable|string|max:50',
                'avatar_url' => 'nullable|string|max:500',
            ]);

            $teacher = Teacher::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Teacher created successfully',
                'data' => ['id' => $teacher->id]
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
                'message' => 'Error creating teacher',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $teacher = Teacher::find($id);

            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }

            $validated = $request->validate([
                'first_name' => 'sometimes|string|max:100',
                'last_name' => 'sometimes|string|max:100',
                'email' => 'sometimes|email|max:255|unique:teachers,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'subject' => 'sometimes|string|max:100',
                'status' => 'nullable|in:Active,On Leave,Inactive',
                'join_date' => 'sometimes|date',
                'date_of_birth' => 'nullable|date',
                'address' => 'nullable|string',
                'education' => 'nullable|string',
                'experience' => 'nullable|string|max:50',
                'avatar_url' => 'nullable|string|max:500',
            ]);

            $teacher->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Teacher updated successfully'
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
                'message' => 'Error updating teacher',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $teacher = Teacher::find($id);

            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }

            $teacher->delete();

            return response()->json([
                'success' => true,
                'message' => 'Teacher deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting teacher',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

