@extends('layouts.dashboard')

@section('content')

<div class="container-fluid py-2">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div>
            <h3 class="fw-bold mb-0">Dashboard</h3>
            <small class="text-muted">{{ now()->format('l, d F Y') }}</small>
        </div>

        <div class="d-flex gap-2">
            @can('students.create')
                <a href="/admin/student-registrations" class="btn btn-primary btn-sm">+ New Admission</a>
            @endcan
            @can('fee-collections.create')
                <a href="/admin/fee-collections" class="btn btn-success btn-sm">+ Collect Fee</a>
            @endcan
            @can('student-attendance.create')
                <a href="/admin/student-attendance" class="btn btn-outline-primary btn-sm">Mark Attendance</a>
            @endcan
        </div>

    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10">🎓</div>
                    <div>
                        <small class="text-muted">Total Students</small>
                        <h3 class="fw-bold mb-0">{{ number_format($students) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10">👨‍🏫</div>
                    <div>
                        <small class="text-muted">Total Teachers</small>
                        <h3 class="fw-bold mb-0">{{ number_format($teachers) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10">📚</div>
                    <div>
                        <small class="text-muted">Courses / Batches</small>
                        <h3 class="fw-bold mb-0">{{ $courses }} <small class="text-muted fs-6">/ {{ $batches }}</small></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10">📝</div>
                    <div>
                        <small class="text-muted">Active Exams</small>
                        <h3 class="fw-bold mb-0">{{ $activeExams }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100 bg-success text-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-white bg-opacity-25">💰</div>
                    <div>
                        <small class="text-white-50">Today's Collection</small>
                        <h4 class="fw-bold mb-0">₹ {{ number_format($todayCollection, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100 bg-dark text-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-white bg-opacity-25">🏦</div>
                    <div>
                        <small class="text-white-50">Total Collection</small>
                        <h4 class="fw-bold mb-0">₹ {{ number_format($totalCollection, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100 bg-danger text-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-white bg-opacity-25">⚠️</div>
                    <div>
                        <small class="text-white-50">Total Dues</small>
                        <h4 class="fw-bold mb-0">₹ {{ number_format($totalDues, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100 bg-primary text-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-white bg-opacity-25">📋</div>
                    <div>
                        <small class="text-white-50">Today's Attendance</small>
                        <h4 class="fw-bold mb-0">
                            {{ $todayPresent }} <small class="fs-6">present</small>
                            / {{ $todayAbsent }} <small class="fs-6">absent</small>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Charts -->
    <div class="row g-3 mb-4">

        <div class="col-lg-5">
            <div class="card chart-card h-100">
                <div class="card-header">💰 Fee Collection — Last 6 Months</div>
                <div class="card-body">
                    <canvas id="collectionChart" height="220"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card chart-card h-100">
                <div class="card-header">📋 Attendance — Last 14 Days</div>
                <div class="card-body">
                    <canvas id="attendanceChart" height="220"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card chart-card h-100">
                <div class="card-header">🎓 Students by Course</div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="courseChart" height="220"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Tables -->
    <div class="row g-3">

        <div class="col-md-6">

            <div class="card chart-card">

                <div class="card-header">🎓 Recent Admissions</div>

                <div class="card-body">

                    <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>
                            <tr>
                                <th>Admission No</th>
                                <th>Name</th>
                                <th>Course</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($recentStudents as $student)
                                <tr>
                                    <td>{{ $student->admission_no }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->course?->name }}</td>
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

            </div>

        </div>

        <div class="col-md-6">

            <div class="card chart-card">

                <div class="card-header">💰 Recent Fee Collection</div>

                <div class="card-body">

                    <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>
                            <tr>
                                <th>Receipt</th>
                                <th>Student</th>
                                <th>Amount</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($recentFees as $fee)
                                <tr>
                                    <td>{{ $fee->receipt_no }}</td>
                                    <td>{{ $fee->student?->name }}</td>
                                    <td class="fw-semibold text-success">₹ {{ number_format($fee->paid_amount, 2) }}</td>
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

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    (function () {

    // runs on full page load AND when arriving via Livewire wire:navigate
    function initDashboardCharts() {

        const collectionEl = document.getElementById('collectionChart');

        if (! window.Chart || ! collectionEl || Chart.getChart(collectionEl)) {
            return;
        }

        new Chart(collectionEl, {
            type: 'bar',
            data: {
                labels: @json($collectionChart['labels']),
                datasets: [{
                    label: 'Collection (₹)',
                    data: @json($collectionChart['values']),
                    backgroundColor: 'rgba(37, 99, 235, .75)',
                    borderRadius: 8,
                }],
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } },
            },
        });

        new Chart(document.getElementById('attendanceChart'), {
            type: 'line',
            data: {
                labels: @json($attendanceChart['labels']),
                datasets: [
                    {
                        label: 'Present',
                        data: @json($attendanceChart['present']),
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, .12)',
                        fill: true,
                        tension: .35,
                    },
                    {
                        label: 'Absent',
                        data: @json($attendanceChart['absent']),
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(220, 38, 38, .10)',
                        fill: true,
                        tension: .35,
                    },
                ],
            },
            options: {
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            },
        });

        new Chart(document.getElementById('courseChart'), {
            type: 'doughnut',
            data: {
                labels: @json($courseChart['labels']),
                datasets: [{
                    data: @json($courseChart['values']),
                    backgroundColor: ['#2563eb', '#16a34a', '#f59e0b', '#dc2626', '#7c3aed', '#0891b2'],
                }],
            },
            options: {
                plugins: { legend: { position: 'bottom' } },
            },
        });

    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDashboardCharts);
    } else {
        initDashboardCharts();
    }

    document.addEventListener('livewire:navigated', initDashboardCharts);

    })();
</script>
@endpush
