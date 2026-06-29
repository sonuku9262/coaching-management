@extends('layouts.dashboard')

@section('content')

<div class="container-fluid py-4">

    <h2 class="mb-4 fw-bold">
        Dashboard
    </h2>

    <div class="row">

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow bg-primary text-white">
                <div class="card-body">
                    <h6>Total Students</h6>
                    <h2>{{ $students }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow bg-success text-white">
                <div class="card-body">
                    <h6>Total Teachers</h6>
                    <h2>{{ $teachers }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow bg-warning text-dark">
                <div class="card-body">
                    <h6>Total Courses</h6>
                    <h2>{{ $courses }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow bg-danger text-white">
                <div class="card-body">
                    <h6>Total Users</h6>
                    <h2>{{ $users }}</h2>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow bg-info text-white">
                <div class="card-body">
                    <h6>Total Subjects</h6>
                    <h2>{{ $subjects }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow bg-secondary text-white">
                <div class="card-body">
                    <h6>Total Batches</h6>
                    <h2>{{ $batches }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow bg-success text-white">
                <div class="card-body">
                    <h6>Today's Collection</h6>
                    <h3>₹ {{ number_format($todayCollection,2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow bg-dark text-white">
                <div class="card-body">
                    <h6>Total Collection</h6>
                    <h3>₹ {{ number_format($totalCollection,2) }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    Recent Students

                </div>

                <div class="card-body">

                    <table class="table table-bordered">

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

                                <td>{{ $student->course->name ?? '-' }}</td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="3" class="text-center">

                                    No Data

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-success text-white">

                    Recent Fee Collection

                </div>

                <div class="card-body">

                    <table class="table table-bordered">

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

                                <td>{{ $fee->student->name ?? '-' }}</td>

                                <td>₹ {{ number_format($fee->paid_amount,2) }}</td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="3" class="text-center">

                                    No Data

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection