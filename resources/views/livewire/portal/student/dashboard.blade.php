<div>
    <div class="container-fluid py-4">

        <h2 class="mb-4 fw-bold">Student Portal</h2>

        @if(! $student)

            <div class="alert alert-warning">
                Your login is not linked to a student registration yet. Please contact the administrator.
            </div>

        @else

            <div class="row">

                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-primary text-white">My Profile</div>
                        <div class="card-body">
                            <p class="mb-1"><strong>{{ $student->name }}</strong> ({{ $student->admission_no }})</p>
                            <p class="mb-1">📘 {{ $student->course?->name }} — {{ $student->batch?->name }}</p>
                            <p class="mb-1">⏰ {{ $student->shift?->name }}</p>
                            <p class="mb-0">📱 {{ $student->mobile }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow bg-success text-white">
                        <div class="card-body">
                            <h6>Attendance</h6>
                            <h2>
                                @if($attendance['total'])
                                    {{ round($attendance['present'] / $attendance['total'] * 100) }}%
                                @else
                                    —
                                @endif
                            </h2>
                            <small>{{ $attendance['present'] }} present / {{ $attendance['absent'] }} absent</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow {{ $feeSummary['balance'] > 0 ? 'bg-danger' : 'bg-info' }} text-white">
                        <div class="card-body">
                            <h6>Fees</h6>
                            <h4>Paid: ₹ {{ number_format($feeSummary['paid'], 2) }}</h4>
                            <small>Balance: ₹ {{ number_format($feeSummary['balance'], 2) }}</small>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-6">

                    <div class="card shadow mb-4">

                        <div class="card-header bg-primary text-white">Recent Attendance</div>

                        <div class="card-body">

                            <table class="table table-bordered">

                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($recentAttendance as $row)
                                        <tr>
                                            <td>{{ \Illuminate\Support\Carbon::parse($row->attendance_date)->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge {{ $row->status === 'Present' ? 'bg-success' : ($row->status === 'Leave' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                    {{ $row->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">No Data</td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="card shadow mb-4">

                        <div class="card-header bg-success text-white">Recent Fee Payments</div>

                        <div class="card-body">

                            <table class="table table-bordered">

                                <thead>
                                    <tr>
                                        <th>Receipt</th>
                                        <th>Type</th>
                                        <th>Paid</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($recentFees as $fee)
                                        <tr>
                                            <td>{{ $fee->receipt_no }}</td>
                                            <td>{{ $fee->feeType?->name }}</td>
                                            <td>₹ {{ number_format($fee->paid_amount, 2) }}</td>
                                            <td>{{ \Illuminate\Support\Carbon::parse($fee->payment_date)->format('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No Data</td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <div class="card shadow mb-4">

                <div class="card-header bg-info text-white">Recent Exam Results</div>

                <div class="card-body">

                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <th>Exam</th>
                                <th>Subject</th>
                                <th>Marks</th>
                                <th>Result</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($recentResults as $result)
                                <tr>
                                    <td>{{ $result->schedule?->exam?->name }}</td>
                                    <td>{{ $result->schedule?->subject?->name }}</td>
                                    <td>
                                        @if($result->is_absent)
                                            <span class="badge bg-secondary">Absent</span>
                                        @else
                                            {{ $result->marks_obtained }} / {{ $result->schedule?->total_marks }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($result->is_absent)
                                            —
                                        @elseif($result->isPass())
                                            <span class="badge bg-success">Pass</span>
                                        @else
                                            <span class="badge bg-danger">Fail</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Results Yet</td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @endif

    </div>
</div>
