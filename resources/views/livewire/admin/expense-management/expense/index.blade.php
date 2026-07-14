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

            <h4 class="mb-0">Expense Management</h4>

            <button class="btn btn-light" wire:click="resetForm" data-bs-toggle="modal" data-bs-target="#expenseModal">
                + Add Expense
            </button>

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search Voucher/Paid To..." wire:model.live="search">
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Voucher No</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Paid To</th>
                            <th>Amount</th>
                            <th>Mode</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($expenses as $expense)
                            <tr>
                                <td>{{ $expense->voucher_no }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                                <td>{{ $expense->category?->name }}</td>
                                <td>{{ $expense->paid_to ?? '-' }}</td>
                                <td class="fw-semibold text-danger">₹ {{ number_format($expense->amount, 2) }}</td>
                                <td>{{ $expense->payment_mode }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" wire:click="edit({{ $expense->id }})"
                                        data-bs-toggle="modal" data-bs-target="#expenseModal">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" wire:click="delete({{ $expense->id }})"
                                        wire:confirm="Delete this expense?">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No Expenses Found</td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $expenses->links() }}
            </div>

        </div>

    </div>

</div>

<!-- Modal -->

<div wire:ignore.self class="modal fade" id="expenseModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form wire:submit.prevent="save">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        {{ $expense_id ? 'Edit Expense' : 'Add Expense' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Category</label>
                        <select class="form-select" wire:model="expense_category_id">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('expense_category_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Amount</label>
                        <input type="number" step="0.01" class="form-control" wire:model="amount">
                        @error('amount')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Expense Date</label>
                        <input type="date" class="form-control" wire:model="expense_date">
                        @error('expense_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
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
                        <label>Paid To</label>
                        <input type="text" class="form-control" wire:model="paid_to" placeholder="Vendor / Person name">
                    </div>

                    <div class="mb-3">
                        <label>Remarks</label>
                        <textarea class="form-control" rows="2" wire:model="remarks"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select class="form-select" wire:model="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        {{ $expense_id ? 'Update Expense' : 'Save Expense' }}
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>
</div>
