<div>
    <div class="container-fluid py-4">

        <h2 class="mb-4 fw-bold">Teacher Portal</h2>

        @if(! $teacher)

            <div class="alert alert-warning">
                Your login is not linked to a teacher profile yet. Please contact the administrator.
            </div>

        @else

            <div class="row">

                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-primary text-white">My Profile</div>
                        <div class="card-body">
                            <p class="mb-1"><strong>{{ $teacher->name }}</strong> ({{ $teacher->employee_id }})</p>
                            <p class="mb-1">{{ $teacher->qualification }}</p>
                            <p class="mb-1">📱 {{ $teacher->mobile }}</p>
                            <p class="mb-0">✉️ {{ $teacher->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow bg-success text-white">
                        <div class="card-body">
                            <h6>Present This Month</h6>
                            <h2>{{ $monthAttendance['present'] }} / {{ $monthAttendance['total'] }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow bg-danger text-white">
                        <div class="card-body">
                            <h6>Absent This Month</h6>
                            <h2>{{ $monthAttendance['absent'] }}</h2>
                        </div>
                    </div>
                </div>

            </div>

            @can('student-attendance.create')
            <a href="/admin/student-attendance" class="btn btn-primary mb-4">
                ✅ Mark Student Attendance
            </a>
            @endcan

            <div class="card shadow">

                <div class="card-header bg-primary text-white">My Recent Attendance</div>

                <div class="card-body">

                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
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
                                    <td>{{ $row->remarks }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No Data</td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @endif

    </div>
</div>
