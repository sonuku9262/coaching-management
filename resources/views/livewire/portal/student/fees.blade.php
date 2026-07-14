<div>
    <div class="container-fluid py-4">

        <h3 class="fw-bold mb-4">💰 My Fees</h3>

        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(! $student)

            <div class="alert alert-warning">
                Your login is not linked to a student registration yet. Please contact the administrator.
            </div>

        @else

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

                <div class="card-header bg-primary text-white fw-bold">Payment History</div>

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
                                    @if($razorpayConfigured)
                                        <th>Action</th>
                                    @endif
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
                                        @if($razorpayConfigured)
                                            <td>
                                                @if($payment->balance > 0)
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        wire:click="createOrder({{ $payment->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="createOrder({{ $payment->id }})">
                                                        💳 Pay Online
                                                    </button>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        @endif
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="{{ $razorpayConfigured ? 10 : 9 }}" class="text-center">No Payments Yet</td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    @if($summary['balance'] > 0)
                        <div class="alert alert-danger mb-0 mt-2">
                            ⚠️ Aapka ₹ {{ number_format($summary['balance'], 2) }} balance due hai —
                            @if($razorpayConfigured)
                                upar "Pay Online" se turant jama karein ya office me jama karein.
                            @else
                                kripya office me jama karein.
                            @endif
                        </div>
                    @endif

                </div>

            </div>

        @endif

    </div>
</div>

@if($razorpayConfigured)
    @push('scripts')
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('razorpay-checkout-open', (event) => {
                    const data = Array.isArray(event) ? event[0] : event;

                    const options = {
                        key: data.key,
                        order_id: data.order_id,
                        amount: data.amount,
                        currency: 'INR',
                        name: data.name,
                        description: data.description,
                        handler: function (response) {
                            @this.call(
                                'verifyPayment',
                                data.fee_collection_id,
                                response.razorpay_payment_id,
                                response.razorpay_order_id,
                                response.razorpay_signature
                            );
                        },
                        theme: { color: '#0d6efd' },
                    };

                    const rzp = new Razorpay(options);
                    rzp.open();
                });
            });
        </script>
    @endpush
@endif
