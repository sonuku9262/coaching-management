<div>
    <div class="container-fluid py-4">

    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">Teacher Salary Payments</h4>

            <button class="btn btn-light" wire:click="resetForm" data-bs-toggle="modal" data-bs-target="#salaryModal">
                + Add Salary Payment
            </button>

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search Teacher..." wire:model.live="search">
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Voucher No</th>
                            <th>Teacher</th>
                            <th>Month</th>
                            <th>Amount</th>
                            <th>Deduction</th>
                            <th>Paid</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->voucher_no }}</td>
                                <td>{{ $payment->teacher?->name }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($payment->salary_month)->format('M Y') }}</td>
                                <td>₹ {{ number_format($payment->amount, 2) }}</td>
                                <td>₹ {{ number_format($payment->deduction, 2) }}</td>
                                <td class="fw-semibold text-success">₹ {{ number_format($payment->paid_amount, 2) }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" wire:click="edit({{ $payment->id }})"
                                        data-bs-toggle="modal" data-bs-target="#salaryModal">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" wire:click="delete({{ $payment->id }})"
                                        wire:confirm="Delete this salary payment?">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No Salary Payments Found</td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $payments->links() }}
            </div>

        </div>

    </div>

</div>

<!-- Modal -->

<div wire:ignore.self class="modal fade" id="salaryModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form wire:submit.prevent="save">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        {{ $salary_payment_id ? 'Edit Salary Payment' : 'Add Salary Payment' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Teacher</label>
                        <select class="form-select" wire:model.live="teacher_id">
                            <option value="">Select Teacher</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->employee_id }})</option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Salary Month</label>
                        <input type="month" class="form-control" wire:model="salary_month">
                        @error('salary_month')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Amount</label>
                        <input type="number" step="0.01" class="form-control" wire:model.live="amount">
                        @error('amount')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Deduction</label>
                        <input type="number" step="0.01" class="form-control" wire:model.live="deduction">
                    </div>

                    <div class="mb-3">
                        <label>Paid Amount</label>
                        <input type="number" step="0.01" class="form-control" wire:model="paid_amount" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Payment Date</label>
                        <input type="date" class="form-control" wire:model="payment_date">
                    </div>

                    <div class="mb-3">
                        <label>Payment Mode</label>
                        <select class="form-select" wire:model="payment_mode">
                            <option value="Cash">Cash</option>
                            <option value="UPI">UPI</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cheque">Cheque</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Remarks</label>
                        <textarea class="form-control" rows="2" wire:model="remarks"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        {{ $salary_payment_id ? 'Update Payment' : 'Save Payment' }}
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>
</div>
