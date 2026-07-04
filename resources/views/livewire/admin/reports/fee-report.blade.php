<div>

    <div class="container-fluid py-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">Fee Collection Report</h4>

            <button class="btn btn-light" wire:click="export">
                ⬇ Export CSV
            </button>

        </div>

        <div class="card-body">

            <div class="row mb-4">

                <div class="col-md-3">
                    <label>From</label>
                    <input type="date" class="form-control" wire:model.live="from_date">
                </div>

                <div class="col-md-3">
                    <label>To</label>
                    <input type="date" class="form-control" wire:model.live="to_date">
                </div>

                <div class="col-md-3">
                    <label>Course</label>
                    <select class="form-select" wire:model.live="course_id">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Payment Mode</label>
                    <select class="form-select" wire:model.live="payment_mode">
                        <option value="">All Modes</option>
                        @foreach($paymentModes as $mode)
                            <option value="{{ $mode }}">{{ $mode }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="row mb-4">

                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body py-2">
                            <small>Total Collected</small>
                            <h4 class="mb-0">₹ {{ number_format($totals['paid'], 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body py-2">
                            <small>Total Discount</small>
                            <h4 class="mb-0">₹ {{ number_format($totals['discount'], 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body py-2">
                            <small>Total Fine</small>
                            <h4 class="mb-0">₹ {{ number_format($totals['fine'], 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body py-2">
                            <small>Outstanding Balance</small>
                            <h4 class="mb-0">₹ {{ number_format($totals['balance'], 2) }}</h4>
                        </div>
                    </div>
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Receipt</th>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Fee Type</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Mode</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($collections as $fee)
                            <tr>
                                <td>{{ $fee->receipt_no }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($fee->payment_date)->format('d M Y') }}</td>
                                <td>{{ $fee->student?->name }} ({{ $fee->student?->admission_no }})</td>
                                <td>{{ $fee->student?->course?->name }}</td>
                                <td>{{ $fee->feeType?->name }}</td>
                                <td>₹ {{ number_format($fee->paid_amount, 2) }}</td>
                                <td class="{{ $fee->balance > 0 ? 'text-danger fw-bold' : '' }}">
                                    ₹ {{ number_format($fee->balance, 2) }}
                                </td>
                                <td>{{ $fee->payment_mode }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No Collections In This Period</td>
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

</div>
