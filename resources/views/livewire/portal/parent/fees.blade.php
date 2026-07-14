<div>
    <div class="container-fluid py-4">

        <h3 class="fw-bold mb-4">💰 Child Fees</h3>

        @if($children->isEmpty())

            <div class="alert alert-warning">
                No student is linked to your account yet. Please contact the administrator.
            </div>

        @else

            @if($children->count() > 1)
                <div class="mb-3" style="max-width: 260px;">
                    <select class="form-select" wire:model.live="student_id">
                        @foreach($children as $child)
                            <option value="{{ $child->id }}">{{ $child->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="row mb-4">

                <div class="col-md-4 col-6 mb-3">
                    <div class="card border-0 shadow bg-success text-white h-100">
                        <div class="card-body py-3">
                            <h6>Total Paid</h6>
                            <h4 class="mb-0">₹ {{ number_format($summary['paid'], 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-6 mb-3">
                    <div class="card border-0 shadow {{ $summary['balance'] > 0 ? 'bg-danger' : 'bg-secondary' }} text-white h-100">
                        <div class="card-body py-3">
                            <h6>Balance Due</h6>
                            <h4 class="mb-0">₹ {{ number_format($summary['balance'], 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12 mb-3">
                    <div class="card border-0 shadow bg-warning text-dark h-100">
                        <div class="card-body py-3">
                            <h6>Discount Received</h6>
                            <h4 class="mb-0">₹ {{ number_format($summary['discount'], 2) }}</h4>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card shadow">

                <div class="card-header bg-primary text-white fw-bold">
                    Payment History — {{ $student->name }}
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead class="table-dark">
                                <tr>
                                    <th>Receipt No</th>
                                    <th>Date</th>
                                    <th>Fee Type</th>
                                    <th>Amount</th>
                                    <th>Discount</th>
                                    <th>Fine</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Mode</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($payments as $payment)

                                    <tr>
                                        <td>{{ $payment->receipt_no }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                                        <td>{{ $payment->feeType?->name }}</td>
                                        <td>₹ {{ number_format($payment->amount, 2) }}</td>
                                        <td>₹ {{ number_format($payment->discount, 2) }}</td>
                                        <td>₹ {{ number_format($payment->fine, 2) }}</td>
                                        <td class="text-success fw-bold">₹ {{ number_format($payment->paid_amount, 2) }}</td>
                                        <td class="{{ $payment->balance > 0 ? 'text-danger fw-bold' : '' }}">
                                            ₹ {{ number_format($payment->balance, 2) }}
                                        </td>
                                        <td>{{ $payment->payment_mode }}</td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="9" class="text-center">No Payments Yet</td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    @if($summary['balance'] > 0)
                        <div class="alert alert-danger mb-0 mt-2">
                            ⚠️ {{ $student->name }} ka ₹ {{ number_format($summary['balance'], 2) }} balance due hai — kripya office me jama karein.
                        </div>
                    @endif

                </div>

            </div>

        @endif

    </div>
</div>
