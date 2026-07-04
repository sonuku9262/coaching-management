<div>
    <div class="container-fluid py-4">

        @if(! $student)

            <div class="alert alert-warning">
                Your login is not linked to a student registration yet. Please contact the administrator.
            </div>

        @else

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

                <div>
                    <h3 class="fw-bold mb-0">👋 Hi, {{ $student->name }}</h3>
                    <small class="text-muted">
                        {{ $student->admission_no }} • {{ $student->course?->name }} — {{ $student->batch?->name }}
                        @if($student->shift) • {{ $student->shift->name }} @endif
                    </small>
                </div>

                <div class="d-flex gap-2">
                    <a href="/student/attendance" class="btn btn-outline-primary btn-sm">📋 Attendance</a>
                    <a href="/student/fees" class="btn btn-outline-success btn-sm">💰 Fees</a>
                    <a href="/student/results" class="btn btn-outline-info btn-sm">📝 Results</a>
                </div>

            </div>

            <!-- Summary Cards -->
            <div class="row">

                <div class="col-md-3 col-6 mb-4">
                    <div class="card border-0 shadow bg-success text-white h-100">
                        <div class="card-body">
                            <h6>Overall Attendance</h6>
                            <h2 class="mb-0">
                                @if($attendance['total'])
                                    {{ round($attendance['present'] / $attendance['total'] * 100) }}%
                                @else
                                    —
                                @endif
                            </h2>
                            <small>{{ $attendance['present'] }}/{{ $attendance['total'] }} days present</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6 mb-4">
                    <div class="card border-0 shadow bg-primary text-white h-100">
                        <div class="card-body">
                            <h6>This Month</h6>
                            <h2 class="mb-0">{{ $monthAttendance['present'] }}/{{ $monthAttendance['total'] }}</h2>
                            <small>days present</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6 mb-4">
                    <div class="card border-0 shadow bg-info text-white h-100">
                        <div class="card-body">
                            <h6>Fees Paid</h6>
                            <h4 class="mb-0">₹ {{ number_format($feeSummary['paid'], 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6 mb-4">
                    <div class="card border-0 shadow {{ $feeSummary['balance'] > 0 ? 'bg-danger' : 'bg-secondary' }} text-white h-100">
                        <div class="card-body">
                            <h6>Balance Due</h6>
                            <h4 class="mb-0">₹ {{ number_format($feeSummary['balance'], 2) }}</h4>
                            @if($feeSummary['balance'] > 0)
                                <small>Kripya jald jama karein</small>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <!-- Notices -->
                <div class="col-md-6 mb-4">

                    <div class="card shadow h-100">

                        <div class="card-header bg-warning text-dark fw-bold">📢 Notices</div>

                        <div class="card-body">

                            @forelse($notices as $notice)

                                <div class="border-bottom pb-2 mb-2">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $notice->title }}</strong>
                                        <small class="text-muted flex-shrink-0 ms-2">
                                            {{ \Illuminate\Support\Carbon::parse($notice->notice_date)->format('d M') }}
                                        </small>
                                    </div>
                                    @if($notice->description)
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($notice->description, 120) }}</small>
                                    @endif
                                </div>

                            @empty

                                <p class="text-muted text-center mb-0">No notices right now.</p>

                            @endforelse

                        </div>

                    </div>

                </div>

                <!-- Upcoming Exams -->
                <div class="col-md-6 mb-4">

                    <div class="card shadow h-100">

                        <div class="card-header bg-primary text-white fw-bold">🗓 Upcoming Exams</div>

                        <div class="card-body">

                            @forelse($upcomingExams as $schedule)

                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">

                                    <div>
                                        <strong>{{ $schedule->subject?->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $schedule->exam?->name }} • Max: {{ $schedule->total_marks }}</small>
                                    </div>

                                    <div class="text-end flex-shrink-0 ms-2">
                                        <span class="badge bg-primary">
                                            {{ \Illuminate\Support\Carbon::parse($schedule->exam_date)->format('d M Y') }}
                                        </span>
                                        @if($schedule->start_time)
                                            <br><small class="text-muted">{{ substr($schedule->start_time, 0, 5) }}</small>
                                        @endif
                                    </div>

                                </div>

                            @empty

                                <p class="text-muted text-center mb-0">No upcoming exams scheduled.</p>

                            @endforelse

                        </div>

                    </div>

                </div>

            </div>

            <!-- Recent Results -->
            <div class="card shadow mb-4">

                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold">📝 Recent Results</span>
                    <a href="/student/results" class="btn btn-light btn-sm">View All</a>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                    <table class="table table-bordered mb-0">

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

            </div>

        @endif

    </div>
</div>
