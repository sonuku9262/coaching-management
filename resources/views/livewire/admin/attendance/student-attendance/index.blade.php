<div class="container-fluid py-4">

    {{-- Success Message --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>

        </div>
    @endif


    {{-- Attendance Entry Card --}}
    <div class="card shadow mb-4">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">
                Student Attendance
            </h4>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Attendance Date
                    </label>

                    <input type="date" class="form-control" wire:model.live="attendance_date">

                </div>

                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Course
                    </label>

                    <select class="form-select" wire:model.live="course_id">

                        <option value="">
                            Select Course
                        </option>

                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">
                                {{ $course->name }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Batch
                    </label>

                    <select class="form-select" wire:model.live="batch_id">

                        <option value="">
                            Select Batch
                        </option>

                        @foreach ($batches as $batch)
                            <option value="{{ $batch->id }}">
                                {{ $batch->name }}
                            </option>
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

                            <th width="220">
                                Attendance
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($students as $index => $student)
                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $student['name'] }}
                                </td>

                                <td>

                                    <select class="form-select" wire:model="students.{{ $index }}.status">

                                        <option value="Present">
                                            Present
                                        </option>

                                        <option value="Absent">
                                            Absent
                                        </option>

                                        <option value="Leave">
                                            Leave
                                        </option>

                                    </select>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="3" class="text-center">

                                    Please Select Course & Batch

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($attendance_id)
                <button class="btn btn-warning" wire:click="save">

                    Update Attendance

                </button>

                <button class="btn btn-secondary" wire:click="resetForm">

                    Cancel

                </button>
            @else
                <button class="btn btn-success" wire:click="save">

                    Save Attendance

                </button>
            @endif

        </div>

    </div>


    {{-- Attendance List Card --}}
    <div class="card shadow">

        <div class="card-header bg-success text-white">

            <h4 class="mb-0">

                Attendance List

            </h4>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>

                            <th>Date</th>

                            <th>Student</th>

                            <th>Course</th>

                            <th>Batch</th>

                            <th>Status</th>

                            <th width="160">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($attendanceList as $attendance)
                            <tr>

                                <td>

                                    {{ $attendance->id }}

                                </td>

                                <td>

                                    {{ $attendance->attendance_date }}

                                </td>

                                <td>

                                    {{ $attendance->student->name ?? '-' }}

                                </td>

                                <td>

                                    {{ $attendance->course->name ?? '-' }}

                                </td>

                                <td>

                                    {{ $attendance->batch->name ?? '-' }}

                                </td>

                                <td>

                                    @if ($attendance->status == 'Present')
                                        <span class="badge bg-success">

                                            Present

                                        </span>
                                    @elseif($attendance->status == 'Absent')
                                        <span class="badge bg-danger">

                                            Absent

                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">

                                            Leave

                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <button wire:click="edit({{ $attendance->id }})" class="btn btn-warning btn-sm">

                                        Edit

                                    </button>

                                    <button wire:click="delete({{ $attendance->id }})" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this attendance?')">

                                        Delete

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center">

                                    No Attendance Found

                                </td>

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

</div>
