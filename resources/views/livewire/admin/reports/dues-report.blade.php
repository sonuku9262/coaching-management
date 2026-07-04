<div>

    <div class="container-fluid py-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">Fee Dues Report</h4>

            <button class="btn btn-light" wire:click="export">
                ⬇ Export CSV
            </button>

        </div>

        <div class="card-body">

            <div class="row mb-4">

                <div class="col-md-4">
                    <label>Search</label>
                    <input type="text" class="form-control" placeholder="Name / Admission No..."
                        wire:model.live="search">
                </div>

                <div class="col-md-4">
                    <label>Course</label>
                    <select class="form-select" wire:model.live="course_id">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body py-2">
                            <small>Total Outstanding</small>
                            <h4 class="mb-0">₹ {{ number_format($totalDue, 2) }}</h4>
                        </div>
                    </div>
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Admission No</th>
                            <th>Student</th>
                            <th>Mobile</th>
                            <th>Course</th>
                            <th>Batch</th>
                            <th>Total Paid</th>
                            <th>Outstanding</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student->admission_no }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->mobile }}</td>
                                <td>{{ $student->course?->name }}</td>
                                <td>{{ $student->batch?->name }}</td>
                                <td>₹ {{ number_format($student->total_paid ?? 0, 2) }}</td>
                                <td class="text-danger fw-bold">₹ {{ number_format($student->outstanding_balance, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No Dues Pending 🎉</td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $students->links() }}
            </div>

        </div>

    </div>

</div>

</div>
