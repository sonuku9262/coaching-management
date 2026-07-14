<div>
    <div class="container-fluid py-4">

        <h2 class="mb-4 fw-bold">Parent Portal</h2>

        @if($children->isEmpty())

            <div class="alert alert-warning">
                No student is linked to your account yet. Please contact the administrator.
            </div>

        @else

            <div class="row">

                @foreach($children as $child)

                    <div class="col-md-6 mb-4">

                        <div class="card shadow">

                            <div class="card-header bg-primary text-white">
                                {{ $child['student']->name }} ({{ $child['student']->admission_no }})
                            </div>

                            <div class="card-body">

                                <p class="mb-2">
                                    📘 {{ $child['student']->course?->name }} — {{ $child['student']->batch?->name }}
                                </p>

                                <div class="row text-center">

                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <h6 class="mb-1">Attendance</h6>
                                            <h4 class="mb-0">
                                                {{ $child['attendancePercent'] !== null ? $child['attendancePercent'] . '%' : '—' }}
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <h6 class="mb-1">Fees Paid</h6>
                                            <h5 class="mb-0">₹ {{ number_format($child['paid'], 2) }}</h5>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="border rounded p-2 {{ $child['balance'] > 0 ? 'border-danger' : '' }}">
                                            <h6 class="mb-1">Balance Due</h6>
                                            <h5 class="mb-0 {{ $child['balance'] > 0 ? 'text-danger' : '' }}">
                                                ₹ {{ number_format($child['balance'], 2) }}
                                            </h5>
                                        </div>
                                    </div>

                                </div>

                                <div class="d-flex gap-2 flex-wrap mt-3">

                                    <a href="/parent/attendance?student_id={{ $child['student']->id }}" class="btn btn-outline-primary btn-sm">
                                        📋 Attendance
                                    </a>

                                    <a href="/parent/fees?student_id={{ $child['student']->id }}" class="btn btn-outline-success btn-sm">
                                        💰 Fees
                                    </a>

                                    <a href="/parent/results?student_id={{ $child['student']->id }}" class="btn btn-outline-secondary btn-sm">
                                        📝 Results
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>
</div>
