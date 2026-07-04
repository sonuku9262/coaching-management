<div>

    <div class="container-fluid py-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">Attendance Report</h4>

            @if($rows->isNotEmpty())
            <button class="btn btn-light" wire:click="export">
                ⬇ Export CSV
            </button>
            @endif

        </div>

        <div class="card-body">

            <div class="row mb-4">

                <div class="col-md-3">
                    <label>From</label>
                    <input type="date" class="form-control" wire:model.live="from_date">
                </div>

                <div class="col-md-3">
                    <label>To</label>
                    <input type="date" class="form-control" wire:model.live="to_date">
                </div>

                <div class="col-md-3">
                    <label>Course</label>
                    <select class="form-select" wire:model.live="course_id">
                        <option value="">-- Select Course --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Batch</label>
                    <select class="form-select" wire:model.live="batch_id">
                        <option value="">All Batches</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            @if(! $course_id)

                <div class="alert alert-info mb-0">
                    Select a course to view the attendance report.
                </div>

            @else

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">
                            <tr>
                                <th>Admission No</th>
                                <th>Student</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Leave</th>
                                <th>Marked Days</th>
                                <th>Attendance %</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($rows as $student)

                                @php
                                    $percent = $student->total_count
                                        ? round($student->present_count / $student->total_count * 100)
                                        : null;
                                @endphp

                                <tr>
                                    <td>{{ $student->admission_no }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td class="text-success fw-bold">{{ $student->present_count }}</td>
                                    <td class="text-danger fw-bold">{{ $student->absent_count }}</td>
                                    <td>{{ $student->leave_count }}</td>
                                    <td>{{ $student->total_count }}</td>
                                    <td>
                                        @if($percent === null)
                                            —
                                        @else
                                            <span class="badge {{ $percent >= 75 ? 'bg-success' : ($percent >= 50 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                {{ $percent }}%
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No Students Found</td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    </div>

</div>

</div>
