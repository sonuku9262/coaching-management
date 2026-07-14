<div class="container-fluid py-4">

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h3 class="fw-bold mb-4">✅ Mark Student Attendance</h3>

    @if($batches->isEmpty())

        <div class="alert alert-warning">
            Aapko abhi tak koi batch assign nahi hui hai. Administrator se sampark karein.
        </div>

    @else

        <div class="card shadow mb-4">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Attendance Entry</h4>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Attendance Date</label>
                        <input type="date" class="form-control" wire:model.live="attendance_date">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Batch</label>
                        <select class="form-select" wire:model.live="batch_id">
                            <option value="">Select Batch</option>
                            @foreach ($batches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">
                            <tr>
                                <th width="60">#</th>
                                <th>Student Name</th>
                                <th width="220">Attendance</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student['name'] }}</td>
                                    <td>
                                        <select class="form-select" wire:model="students.{{ $index }}.status">
                                            <option value="Present">Present</option>
                                            <option value="Absent">Absent</option>
                                            <option value="Leave">Leave</option>
                                        </select>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Please Select Batch</td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                @if(! empty($students))
                    <button class="btn btn-success" wire:click="save">
                        Save Attendance
                    </button>
                @endif

            </div>

        </div>

        <div class="card shadow">

            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Recent Attendance</h4>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Student</th>
                                <th>Batch</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($attendanceList as $attendance)
                                <tr>
                                    <td>{{ $attendance->attendance_date }}</td>
                                    <td>{{ $attendance->student->name ?? '-' }}</td>
                                    <td>{{ $attendance->batch->name ?? '-' }}</td>
                                    <td>
                                        @if ($attendance->status == 'Present')
                                            <span class="badge bg-success">Present</span>
                                        @elseif($attendance->status == 'Absent')
                                            <span class="badge bg-danger">Absent</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Leave</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Attendance Found</td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">
                    {{ $attendanceList->links() }}
                </div>

            </div>

        </div>

    @endif

</div>
