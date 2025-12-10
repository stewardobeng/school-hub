<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return $this->indexApi($request);
        }

        try {
            $query = Payment::query()
                ->join('students', 'payments.student_id', '=', 'students.id');

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('payments.id', 'LIKE', "%{$search}%")
                      ->orWhere('payments.transaction_id', 'LIKE', "%{$search}%")
                      ->orWhere('students.first_name', 'LIKE', "%{$search}%")
                      ->orWhere('students.last_name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status')) {
                $query->where('payments.status', $request->get('status'));
            }

            if ($request->has('type')) {
                $query->where('payments.type', $request->get('type'));
            }

            if ($request->has('student_id')) {
                $query->where('payments.student_id', $request->get('student_id'));
            }

            if ($request->has('start_date')) {
                $query->where('payments.payment_date', '>=', $request->get('start_date'));
            }

            if ($request->has('end_date')) {
                $query->where('payments.payment_date', '<=', $request->get('end_date'));
            }

            $payments = $query->selectRaw('payments.*, students.first_name as student_first_name, students.last_name as student_last_name, students.id as student_id')
                ->orderBy('payments.payment_date', 'desc')
                ->get();

            return view('admin.payments.index', [
                'title' => 'Payment',
                'userRole' => 'admin',
                'payments' => $payments
            ]);
        } catch (\Exception $e) {
            return view('admin.payments.index', [
                'title' => 'Payment',
                'userRole' => 'admin',
                'payments' => collect([])
            ]);
        }
    }

    private function indexApi(Request $request): JsonResponse
    {
        try {
            $query = Payment::query()
                ->join('students', 'payments.student_id', '=', 'students.id');

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('payments.id', 'LIKE', "%{$search}%")
                      ->orWhere('payments.transaction_id', 'LIKE', "%{$search}%")
                      ->orWhere('students.first_name', 'LIKE', "%{$search}%")
                      ->orWhere('students.last_name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status')) {
                $query->where('payments.status', $request->get('status'));
            }

            if ($request->has('type')) {
                $query->where('payments.type', $request->get('type'));
            }

            if ($request->has('student_id')) {
                $query->where('payments.student_id', $request->get('student_id'));
            }

            if ($request->has('start_date')) {
                $query->where('payments.payment_date', '>=', $request->get('start_date'));
            }

            if ($request->has('end_date')) {
                $query->where('payments.payment_date', '<=', $request->get('end_date'));
            }

            $payments = $query->selectRaw('payments.*, students.first_name as student_first_name, students.last_name as student_last_name, students.id as student_id')
                ->orderBy('payments.payment_date', 'desc')
                ->orderBy('payments.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $payments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching payments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $payment = Payment::with('student')->find($id);

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            $data = $payment->toArray();
            $data['student_first_name'] = $payment->student ? $payment->student->first_name : null;
            $data['student_last_name'] = $payment->student ? $payment->student->last_name : null;
            $data['student_email'] = $payment->student ? $payment->student->email : null;
            $data['student_phone'] = $payment->student ? $payment->student->phone : null;
            $data['student_grade'] = $payment->student ? $payment->student->grade : null;

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id' => 'required|string|max:20|unique:payments,id',
                'student_id' => 'required|string|max:20|exists:students,id',
                'amount' => 'required|numeric|min:0',
                'type' => 'required|in:Tuition Fee,Library Fee,Exam Fee,Other',
                'method' => 'required|in:Credit Card,Bank Transfer,Cash,Online Payment',
                'payment_date' => 'required|date',
                'status' => 'nullable|in:Paid,Pending,Failed,Refunded',
                'transaction_id' => 'nullable|string|max:100|unique:payments,transaction_id',
                'notes' => 'nullable|string',
            ]);

            $payment = Payment::create(array_merge($validated, [
                'status' => $validated['status'] ?? 'Pending'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Payment created successfully',
                'data' => ['id' => $payment->id]
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
                'message' => 'Error creating payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $payment = Payment::find($id);

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            $validated = $request->validate([
                'student_id' => 'sometimes|string|max:20|exists:students,id',
                'amount' => 'sometimes|numeric|min:0',
                'type' => 'sometimes|in:Tuition Fee,Library Fee,Exam Fee,Other',
                'method' => 'sometimes|in:Credit Card,Bank Transfer,Cash,Online Payment',
                'payment_date' => 'sometimes|date',
                'status' => 'nullable|in:Paid,Pending,Failed,Refunded',
                'transaction_id' => 'nullable|string|max:100|unique:payments,transaction_id,' . $id,
                'notes' => 'nullable|string',
            ]);

            $payment->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully'
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
                'message' => 'Error updating payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $payment = Payment::find($id);

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            $payment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Payment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

