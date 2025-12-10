<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return $this->indexApi($request);
        }

        try {
            $query = Attendance::query()
                ->leftJoin('teachers', 'attendance.teacher_id', '=', 'teachers.id');

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('attendance.class_name', 'LIKE', "%{$search}%")
                      ->orWhere('attendance.id', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status')) {
                $query->where('attendance.status', $request->get('status'));
            }

            if ($request->has('teacher_id')) {
                $query->where('attendance.teacher_id', $request->get('teacher_id'));
            }

            if ($request->has('class_name')) {
                $query->where('attendance.class_name', 'LIKE', "%{$request->get('class_name')}%");
            }

            if ($request->has('start_date')) {
                $query->where('attendance.attendance_date', '>=', $request->get('start_date'));
            }

            if ($request->has('end_date')) {
                $query->where('attendance.attendance_date', '<=', $request->get('end_date'));
            }

            $attendance = $query->selectRaw('attendance.*, teachers.first_name as teacher_first_name, teachers.last_name as teacher_last_name')
                ->orderBy('attendance.attendance_date', 'desc')
                ->orderBy('attendance.created_at', 'desc')
                ->get();

            return view('admin.attendance.index', [
                'title' => 'Attendance',
                'userRole' => 'admin',
                'attendance' => $attendance
            ]);
        } catch (\Exception $e) {
            return view('admin.attendance.index', [
                'title' => 'Attendance',
                'userRole' => 'admin',
                'attendance' => collect([])
            ]);
        }
    }

    private function indexApi(Request $request): JsonResponse
    {
        try {
            $query = Attendance::query()
                ->leftJoin('teachers', 'attendance.teacher_id', '=', 'teachers.id');

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('attendance.class_name', 'LIKE', "%{$search}%")
                      ->orWhere('attendance.id', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status')) {
                $query->where('attendance.status', $request->get('status'));
            }

            if ($request->has('teacher_id')) {
                $query->where('attendance.teacher_id', $request->get('teacher_id'));
            }

            if ($request->has('class_name')) {
                $query->where('attendance.class_name', 'LIKE', "%{$request->get('class_name')}%");
            }

            if ($request->has('start_date')) {
                $query->where('attendance.attendance_date', '>=', $request->get('start_date'));
            }

            if ($request->has('end_date')) {
                $query->where('attendance.attendance_date', '<=', $request->get('end_date'));
            }

            $attendance = $query->selectRaw('attendance.*, teachers.first_name as teacher_first_name, teachers.last_name as teacher_last_name')
                ->orderBy('attendance.attendance_date', 'desc')
                ->orderBy('attendance.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $attendance
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $attendance = Attendance::with('teacher')->find($id);

            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance record not found'
                ], 404);
            }

            $data = $attendance->toArray();
            $data['teacher_first_name'] = $attendance->teacher ? $attendance->teacher->first_name : null;
            $data['teacher_last_name'] = $attendance->teacher ? $attendance->teacher->last_name : null;
            $data['teacher_email'] = $attendance->teacher ? $attendance->teacher->email : null;
            $data['teacher_phone'] = $attendance->teacher ? $attendance->teacher->phone : null;

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id' => 'required|string|max:20|unique:attendance,id',
                'class_name' => 'required|string|max:255',
                'teacher_id' => 'nullable|string|max:20|exists:teachers,id',
                'attendance_date' => 'required|date',
                'total_students' => 'required|integer|min:0',
                'present' => 'required|integer|min:0',
                'absent' => 'required|integer|min:0',
                'late' => 'nullable|integer|min:0',
                'status' => 'nullable|in:Completed,Pending',
                'notes' => 'nullable|string',
            ]);

            // Validate numbers
            $present = $validated['present'];
            $absent = $validated['absent'];
            $late = $validated['late'] ?? 0;
            $total = $validated['total_students'];

            if ($present + $absent + $late !== $total) {
                return response()->json([
                    'success' => false,
                    'message' => 'Present + Absent + Late must equal Total Students'
                ], 400);
            }

            $attendance = Attendance::create(array_merge($validated, [
                'status' => $validated['status'] ?? 'Pending',
                'late' => $late
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Attendance record created successfully',
                'data' => ['id' => $attendance->id]
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
                'message' => 'Error creating attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $attendance = Attendance::find($id);

            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance record not found'
                ], 404);
            }

            $validated = $request->validate([
                'class_name' => 'sometimes|string|max:255',
                'teacher_id' => 'nullable|string|max:20|exists:teachers,id',
                'attendance_date' => 'sometimes|date',
                'total_students' => 'sometimes|integer|min:0',
                'present' => 'sometimes|integer|min:0',
                'absent' => 'sometimes|integer|min:0',
                'late' => 'nullable|integer|min:0',
                'status' => 'nullable|in:Completed,Pending',
                'notes' => 'nullable|string',
            ]);

            // Validate numbers if all are provided
            if (isset($validated['present']) && isset($validated['absent']) && isset($validated['total_students'])) {
                $present = $validated['present'];
                $absent = $validated['absent'];
                $late = $validated['late'] ?? $attendance->late;
                $total = $validated['total_students'];

                if ($present + $absent + $late !== $total) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Present + Absent + Late must equal Total Students'
                    ], 400);
                }
            }

            $attendance->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Attendance record updated successfully'
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
                'message' => 'Error updating attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $attendance = Attendance::find($id);

            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance record not found'
                ], 404);
            }

            $attendance->delete();

            return response()->json([
                'success' => true,
                'message' => 'Attendance record deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

