<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return $this->indexApi($request);
        }

        try {
            $query = Exam::query()
                ->leftJoin('courses', 'exams.course_id', '=', 'courses.id')
                ->leftJoin('teachers', 'courses.teacher_id', '=', 'teachers.id');

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('exams.title', 'LIKE', "%{$search}%")
                      ->orWhere('exams.id', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status')) {
                $query->where('exams.status', $request->get('status'));
            }

            if ($request->has('type')) {
                $query->where('exams.type', $request->get('type'));
            }

            if ($request->has('course_id')) {
                $query->where('exams.course_id', $request->get('course_id'));
            }

            if ($request->has('grade')) {
                $query->where('exams.grade', $request->get('grade'));
            }

            $exams = $query->selectRaw('exams.*, courses.name as course_name, courses.code as course_code, teachers.first_name as teacher_first_name, teachers.last_name as teacher_last_name')
                ->orderBy('exams.exam_date', 'desc')
                ->orderBy('exams.exam_time', 'desc')
                ->get();

            return view('admin.exams.index', [
                'title' => 'Exam',
                'userRole' => 'admin',
                'exams' => $exams
            ]);
        } catch (\Exception $e) {
            return view('admin.exams.index', [
                'title' => 'Exam',
                'userRole' => 'admin',
                'exams' => collect([])
            ]);
        }
    }

    private function indexApi(Request $request): JsonResponse
    {
        try {
            $query = Exam::query()
                ->leftJoin('courses', 'exams.course_id', '=', 'courses.id')
                ->leftJoin('teachers', 'courses.teacher_id', '=', 'teachers.id');

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('exams.title', 'LIKE', "%{$search}%")
                      ->orWhere('exams.id', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status')) {
                $query->where('exams.status', $request->get('status'));
            }

            if ($request->has('type')) {
                $query->where('exams.type', $request->get('type'));
            }

            if ($request->has('course_id')) {
                $query->where('exams.course_id', $request->get('course_id'));
            }

            if ($request->has('grade')) {
                $query->where('exams.grade', $request->get('grade'));
            }

            $exams = $query->selectRaw('exams.*, courses.name as course_name, courses.code as course_code, teachers.first_name as teacher_first_name, teachers.last_name as teacher_last_name')
                ->orderBy('exams.exam_date', 'desc')
                ->orderBy('exams.exam_time', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $exams
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching exams',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $exam = Exam::with('course.teacher')->find($id);

            if (!$exam) {
                return response()->json([
                    'success' => false,
                    'message' => 'Exam not found'
                ], 404);
            }

            // Get exam results
            $results = DB::table('exam_results')
                ->join('students', 'exam_results.student_id', '=', 'students.id')
                ->where('exam_results.exam_id', $id)
                ->select('exam_results.*', 'students.first_name as student_first_name', 'students.last_name as student_last_name', 'students.id as student_id')
                ->orderBy('students.last_name')
                ->orderBy('students.first_name')
                ->get();

            $data = $exam->toArray();
            $data['course_name'] = $exam->course ? $exam->course->name : null;
            $data['course_code'] = $exam->course ? $exam->course->code : null;
            $data['teacher_first_name'] = $exam->course && $exam->course->teacher ? $exam->course->teacher->first_name : null;
            $data['teacher_last_name'] = $exam->course && $exam->course->teacher ? $exam->course->teacher->last_name : null;
            $data['results'] = $results;

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching exam',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id' => 'required|string|max:20|unique:exams,id',
                'title' => 'required|string|max:255',
                'course_id' => 'nullable|string|max:20|exists:courses,id',
                'grade' => 'required|string|max:20',
                'exam_date' => 'required|date',
                'exam_time' => 'required',
                'duration' => 'required|string|max:50',
                'type' => 'required|in:Midterm,Final,Quiz,Unit Test,Practical,Assessment',
                'status' => 'nullable|in:Scheduled,In Progress,Completed,Cancelled',
                'max_score' => 'nullable|integer|min:0',
            ]);

            $exam = Exam::create(array_merge($validated, [
                'status' => $validated['status'] ?? 'Scheduled',
                'max_score' => $validated['max_score'] ?? 100
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Exam created successfully',
                'data' => ['id' => $exam->id]
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
                'message' => 'Error creating exam',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $exam = Exam::find($id);

            if (!$exam) {
                return response()->json([
                    'success' => false,
                    'message' => 'Exam not found'
                ], 404);
            }

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'course_id' => 'nullable|string|max:20|exists:courses,id',
                'grade' => 'sometimes|string|max:20',
                'exam_date' => 'sometimes|date',
                'exam_time' => 'sometimes',
                'duration' => 'sometimes|string|max:50',
                'type' => 'sometimes|in:Midterm,Final,Quiz,Unit Test,Practical,Assessment',
                'status' => 'nullable|in:Scheduled,In Progress,Completed,Cancelled',
                'max_score' => 'nullable|integer|min:0',
            ]);

            $exam->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Exam updated successfully'
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
                'message' => 'Error updating exam',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $exam = Exam::find($id);

            if (!$exam) {
                return response()->json([
                    'success' => false,
                    'message' => 'Exam not found'
                ], 404);
            }

            $exam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Exam deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting exam',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

