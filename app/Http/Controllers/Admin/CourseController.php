<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return $this->indexApi($request);
        }

        try {
            $query = Course::with('teacher')
                ->select('courses.*')
                ->leftJoin('teachers', 'courses.teacher_id', '=', 'teachers.id');

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('courses.name', 'LIKE', "%{$search}%")
                      ->orWhere('courses.code', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status')) {
                $query->where('courses.status', $request->get('status'));
            }

            if ($request->has('grade')) {
                $query->where('courses.grade', $request->get('grade'));
            }

            if ($request->has('teacher_id')) {
                $query->where('courses.teacher_id', $request->get('teacher_id'));
            }

            $courses = $query->selectRaw('courses.*, teachers.first_name as teacher_first_name, teachers.last_name as teacher_last_name, (SELECT COUNT(*) FROM course_enrollments WHERE course_id = courses.id AND status = "Active") as student_count')
                ->orderBy('courses.created_at', 'desc')
                ->get();

            return view('admin.courses.index', [
                'title' => 'Courses',
                'userRole' => 'admin',
                'courses' => $courses
            ]);
        } catch (\Exception $e) {
            return view('admin.courses.index', [
                'title' => 'Courses',
                'userRole' => 'admin',
                'courses' => collect([])
            ]);
        }
    }

    private function indexApi(Request $request): JsonResponse
    {
        try {
            $query = Course::with('teacher')
                ->select('courses.*')
                ->leftJoin('teachers', 'courses.teacher_id', '=', 'teachers.id');

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('courses.name', 'LIKE', "%{$search}%")
                      ->orWhere('courses.code', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status')) {
                $query->where('courses.status', $request->get('status'));
            }

            if ($request->has('grade')) {
                $query->where('courses.grade', $request->get('grade'));
            }

            if ($request->has('teacher_id')) {
                $query->where('courses.teacher_id', $request->get('teacher_id'));
            }

            $courses = $query->selectRaw('courses.*, teachers.first_name as teacher_first_name, teachers.last_name as teacher_last_name, (SELECT COUNT(*) FROM course_enrollments WHERE course_id = courses.id AND status = "Active") as student_count')
                ->orderBy('courses.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $course = Course::with('teacher')->find($id);

            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found'
                ], 404);
            }

            // Get enrolled students
            $students = DB::table('students')
                ->join('course_enrollments', 'students.id', '=', 'course_enrollments.student_id')
                ->where('course_enrollments.course_id', $id)
                ->where('course_enrollments.status', 'Active')
                ->select('students.*', 'course_enrollments.enrollment_date', DB::raw("course_enrollments.status as enrollment_status"))
                ->orderBy('students.last_name')
                ->orderBy('students.first_name')
                ->get();

            // Get course exams
            $exams = $course->exams()->orderBy('exam_date', 'desc')->get();

            $data = $course->toArray();
            $data['teacher_first_name'] = $course->teacher ? $course->teacher->first_name : null;
            $data['teacher_last_name'] = $course->teacher ? $course->teacher->last_name : null;
            $data['teacher_email'] = $course->teacher ? $course->teacher->email : null;
            $data['teacher_phone'] = $course->teacher ? $course->teacher->phone : null;
            $data['students'] = $students;
            $data['exams'] = $exams;

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id' => 'required|string|max:20|unique:courses,id',
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:courses,code',
                'grade' => 'required|string|max:20',
                'teacher_id' => 'nullable|string|max:20|exists:teachers,id',
                'credits' => 'required|integer',
                'duration' => 'required|string|max:50',
                'schedule' => 'nullable|string',
                'status' => 'nullable|in:Active,Archived',
                'description' => 'nullable|string',
            ]);

            $course = Course::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Course created successfully',
                'data' => ['id' => $course->id]
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
                'message' => 'Error creating course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $course = Course::find($id);

            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'code' => 'sometimes|string|max:50|unique:courses,code,' . $id,
                'grade' => 'sometimes|string|max:20',
                'teacher_id' => 'nullable|string|max:20|exists:teachers,id',
                'credits' => 'sometimes|integer',
                'duration' => 'sometimes|string|max:50',
                'schedule' => 'nullable|string',
                'status' => 'nullable|in:Active,Archived',
                'description' => 'nullable|string',
            ]);

            $course->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully'
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
                'message' => 'Error updating course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $course = Course::find($id);

            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found'
                ], 404);
            }

            $course->delete();

            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting course',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

