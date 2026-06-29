<div>
    <div class="container-fluid py-4">

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Fee Collection</h4>

                <button class="btn btn-light" wire:click="resetForm" data-bs-toggle="modal"
                    data-bs-target="#feeCollectionModal">

                    + Collect Fee

                </button>

            </div>

            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-4">

                        <input type="text" class="form-control" placeholder="Search Student..."
                            wire:model.live="search">

                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>
                                <th>Receipt No</th>
                                <th>Student</th>
                                <th>Fee Type</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Payment Mode</th>
                                <th>Date</th>
                                <th width="180">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($collections as $collection)
                                <tr>

                                    <td>{{ $collection->id }}</td>

                                    <td>{{ $collection->receipt_no }}</td>

                                    <td>{{ $collection->student->name ?? '-' }}</td>

                                    <td>{{ $collection->feeType->name ?? '-' }}</td>

                                    <td>₹ {{ number_format($collection->paid_amount, 2) }}</td>

                                    <td>₹ {{ number_format($collection->balance, 2) }}</td>

                                    <td>{{ $collection->payment_mode }}</td>

                                    <td>{{ $collection->payment_date }}</td>

                                    <td>

                                        <button class="btn btn-warning btn-sm" wire:click="edit({{ $collection->id }})"
                                            data-bs-toggle="modal" data-bs-target="#feeCollectionModal">

                                            Edit

                                        </button>

                                        <button class="btn btn-danger btn-sm"
                                            wire:click="delete({{ $collection->id }})">

                                            Delete

                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9" class="text-center">

                                        No Fee Collection Found

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">

                    {{ $collections->links() }}

                </div>

            </div>

        </div>

    </div>

    <!-- Modal -->

    <div wire:ignore.self class="modal fade" id="feeCollectionModal" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <form wire:submit.prevent="save">

                    <div class="modal-header bg-primary text-white">

                        <h5 class="modal-title">

                            {{ $fee_collection_id ? 'Edit Fee Collection' : 'Collect Fee' }}

                        </h5>

                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label>Student</label>

                                <select class="form-select" wire:model="student_registration_id">

                                    <option value="">Select Student</option>

                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">
                                            {{ $student->admission_no }} - {{ $student->name }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Fee Type</label>

                                <select class="form-select" wire:model="fee_type_id">

                                    <option value="">Select Fee Type</option>

                                    @foreach ($feeTypes as $fee)
                                        <option value="{{ $fee->id }}">
                                            {{ $fee->name }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>Amount</label>

                                <input type="number" class="form-control" wire:model="amount" readonly>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>Discount</label>

                                <input type="number" class="form-control" wire:model.live="discount">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>Fine</label>

                                <input type="number" class="form-control" wire:model.live="fine">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>Paid Amount</label>

                                <input type="number" class="form-control" wire:model.live="paid_amount">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>Balance</label>

                                <input type="number" class="form-control" wire:model="balance" readonly>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>Payment Mode</label>

                                <select class="form-select" wire:model="payment_mode">

                                    <option>Cash</option>
                                    <option>UPI</option>
                                    <option>Card</option>
                                    <option>Bank Transfer</option>

                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Payment Date</label>

                                <input type="date" class="form-control" wire:model="payment_date">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Remarks</label>

                                <input type="text" class="form-control" wire:model="remarks">

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                            Close

                        </button>

                        <button class="btn btn-primary">

                            {{ $fee_collection_id ? 'Update Fee' : 'Save Fee' }}

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</div>
