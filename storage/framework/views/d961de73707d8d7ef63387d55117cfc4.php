

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="paymentManagement()">
    <!-- Stats Row -->
    <?php
        $totalRevenue = $payments->where('status', 'Paid')->sum('amount');
        $monthlyRevenue = $payments->where('status', 'Paid')->filter(function($p) { return \Carbon\Carbon::parse($p->payment_date)->isCurrentMonth(); })->sum('amount');
        $pendingPayments = $payments->where('status', 'Pending')->count();
    ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-green-100">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Total Revenue</p>
                <p class="text-2xl font-bold text-foreground">$<?php echo e(number_format($totalRevenue, 2)); ?></p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-blue-100">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">This Month</p>
                <p class="text-2xl font-bold text-foreground">$<?php echo e(number_format($monthlyRevenue, 2)); ?></p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-amber-100">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Pending</p>
                <p class="text-2xl font-bold text-foreground"><?php echo e($pendingPayments); ?></p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-card rounded-xl shadow-sm border border-border/50">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold">Payment Management</h2>
                    <p class="text-sm text-muted-foreground mt-1">Track and manage all payment transactions</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchPayments()" placeholder="Search payments..." 
                               class="pl-10 pr-4 py-2 w-64 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <select x-model="statusFilter" @change="fetchPayments()" class="w-40 px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        <option value="all">All Status</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                    <button @click="openAddDialog()" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Record Payment
                        </span>
                    </button>
                </div>
            </div>
            
            <div class="rounded-md border overflow-hidden">
                <table class="w-full">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Payment ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-muted-foreground uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-muted/50">
                                <td class="px-6 py-4 font-mono text-sm"><?php echo e($payment->id); ?></td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium"><?php echo e(($payment->student_first_name ?? '') . ' ' . ($payment->student_last_name ?? '')); ?></div>
                                        <div class="text-sm text-muted-foreground"><?php echo e($payment->student_id ?? 'N/A'); ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded border"><?php echo e($payment->type ?? 'N/A'); ?></span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-green-600">$<?php echo e(number_format($payment->amount ?? 0, 2)); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo e($payment->method ?? 'N/A'); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo e($payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : 'N/A'); ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full <?php echo e($payment->status === 'Paid' ? 'bg-green-100 text-green-800' : ($payment->status === 'Pending' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800')); ?>">
                                        <?php echo e($payment->status ?? 'Pending'); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <button @click="openDetailsDialog('<?php echo e($payment->id); ?>')" class="text-primary hover:text-primary/80">View</button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-muted-foreground">No payments found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Payment Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showAddModal = false">
        <div class="bg-card rounded-xl shadow-lg border border-border/50 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Record Payment</h3>
                    <button @click="showAddModal = false" class="text-muted-foreground hover:text-foreground">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="handleAddSubmit()" class="space-y-4">
                    <div x-show="errorMessage" class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" x-text="errorMessage"></div>
                    <div x-show="successMessage" class="p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm" x-text="successMessage"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Student *</label>
                            <select x-model="formData.student_id" required class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Select student</option>
                                <?php $__currentLoopData = \App\Models\Student::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($student->id); ?>"><?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?> (<?php echo e($student->id); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Amount *</label>
                            <input type="number" step="0.01" x-model="formData.amount" required placeholder="1250.00" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Payment Type *</label>
                            <select x-model="formData.type" required class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Select type</option>
                                <option value="Tuition Fee">Tuition Fee</option>
                                <option value="Library Fee">Library Fee</option>
                                <option value="Exam Fee">Exam Fee</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Payment Method *</label>
                            <select x-model="formData.method" required class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Select method</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cash">Cash</option>
                                <option value="Online Payment">Online Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Payment Date *</label>
                        <input type="date" x-model="formData.payment_date" required 
                               class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <div class="flex gap-3 justify-end pt-4">
                        <button type="button" @click="showAddModal = false" :disabled="isLoading" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors disabled:opacity-50">Cancel</button>
                        <button type="submit" :disabled="isLoading" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors disabled:opacity-50">
                            <span x-show="!isLoading">Record Payment</span>
                            <span x-show="isLoading">Recording...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Details Modal -->
    <div x-show="showDetailsModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showDetailsModal = false">
        <div class="bg-card rounded-xl shadow-lg border border-border/50 max-w-2xl w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Payment Details
                    </h3>
                    <button @click="showDetailsModal = false" class="text-muted-foreground hover:text-foreground">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div x-show="selectedPayment" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-muted-foreground">Payment ID</p>
                            <p class="font-mono font-medium" x-text="selectedPayment?.id"></p>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Transaction ID</p>
                            <p class="font-mono font-medium" x-text="selectedPayment?.transaction_id || 'N/A'"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-muted-foreground">Student</p>
                            <p class="font-medium" x-text="(selectedPayment?.student_first_name || '') + ' ' + (selectedPayment?.student_last_name || '')"></p>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Amount</p>
                            <p class="text-2xl font-bold text-green-600" x-text="'$' + (selectedPayment?.amount?.toFixed(2) || '0.00')"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-muted-foreground">Payment Type</p>
                            <span class="px-2 py-1 text-xs rounded border" x-text="selectedPayment?.type || 'N/A'"></span>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Payment Method</p>
                            <span class="px-2 py-1 text-xs rounded bg-muted" x-text="selectedPayment?.method || 'N/A'"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-muted-foreground">Date</p>
                            <p class="font-medium" x-text="selectedPayment?.payment_date || 'N/A'"></p>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Status</p>
                            <span class="px-2 py-1 text-xs rounded-full" 
                                  :class="selectedPayment?.status === 'Paid' ? 'bg-green-100 text-green-800' : (selectedPayment?.status === 'Pending' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800')"
                                  x-text="selectedPayment?.status || 'N/A'"></span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 justify-end pt-4">
                    <button @click="showDetailsModal = false" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function paymentManagement() {
    return {
        searchQuery: '',
        statusFilter: 'all',
        showAddModal: false,
        showDetailsModal: false,
        selectedPayment: null,
        formData: {
            student_id: '',
            amount: '',
            type: '',
            method: '',
            payment_date: new Date().toISOString().split('T')[0]
        },
        payments: <?php echo json_encode($payments, 15, 512) ?>,
        openAddDialog() {
            this.resetForm();
            this.showAddModal = true;
        },
        async openDetailsDialog(paymentId) {
            try {
                const response = await fetch(`/api/payments/${paymentId}`);
                const data = await response.json();
                if (data.success) {
                    this.selectedPayment = data.data;
                    this.showDetailsModal = true;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        },
        isLoading: false,
        errorMessage: '',
        successMessage: '',
        async handleAddSubmit() {
            this.isLoading = true;
            this.errorMessage = '';
            this.successMessage = '';
            try {
                const response = await fetch('/api/payments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id: 'PAY' + String(Date.now()).slice(-6),
                        transaction_id: 'TXN-' + new Date().getFullYear() + '-' + String(Math.floor(Math.random() * 1000000)).padStart(6, '0'),
                        status: 'Paid',
                        ...this.formData,
                        amount: parseFloat(this.formData.amount)
                    })
                });
                const data = await response.json();
                if (data.success) {
                    this.successMessage = 'Payment recorded successfully!';
                    this.showAddModal = false;
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.errorMessage = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Failed to record payment');
                    this.isLoading = false;
                }
            } catch (error) {
                this.errorMessage = 'An error occurred. Please try again.';
                this.isLoading = false;
                console.error('Error:', error);
            }
        },
        fetchPayments() {
            const query = this.searchQuery.toLowerCase().trim();
            const status = this.statusFilter;
            if (!query && status === 'all') {
                return;
            }
            const params = new URLSearchParams();
            if (query) params.set('search', query);
            if (status !== 'all') params.set('status', status);
            if (params.toString()) {
                window.location.href = window.location.pathname + '?' + params.toString();
            }
        },
        resetForm() {
            this.formData = {
                student_id: '',
                amount: '',
                type: '',
                method: '',
                payment_date: new Date().toISOString().split('T')[0]
            };
        }
    }
}
</script>
<style>
[x-cloak] { display: none !important; }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', ['title' => 'Payment', 'userRole' => 'admin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\school-hub\resources\views/admin/payments/index.blade.php ENDPATH**/ ?>