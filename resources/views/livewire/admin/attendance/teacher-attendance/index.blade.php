<div>
    <div class="container-fluid py-4">

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button class="btn-close" data-bs-dismiss="alert"></button>

            </div>
        @endif

        {{-- Teacher Attendance Form --}}
        <div class="card shadow mb-4">

            <div class="card-header bg-primary text-white">

                <h4 class="mb-0">
                    Teacher Attendance
                </h4>

            </div>

            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-3">

                        <label>Attendance Date</label>

                        <input type="date" class="form-control" wire:model.live="attendance_date">

                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">

                            <tr>

                                <th>#</th>

                                <th>Teacher Name</th>

                                <th width="220">Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($teachers as $index => $teacher)
                                <tr>

                                    <td>{{ $index + 1 }}</td>

                                    <td>{{ $teacher['name'] }}</td>

                                    <td>

                                        <select class="form-select" wire:model="teachers.{{ $index }}.status">

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

                                        No Teacher Found

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

        {{-- Attendance List --}}
        <div class="card shadow">

            <div class="card-header bg-success text-white">

                <h4 class="mb-0">

                    Teacher Attendance List

                </h4>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>

                                <th>Date</th>

                                <th>Teacher</th>

                                <th>Status</th>

                                <th width="150">

                                    Action

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($attendanceList as $attendance)
                                <tr>

                                    <td>{{ $attendance->id }}</td>

                                    <td>{{ $attendance->attendance_date }}</td>

                                    <td>{{ $attendance->teacher->name ?? '-' }}</td>

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

                                        <button wire:click="delete({{ $attendance->id }})"
                                            onclick="return confirm('Are you sure you want to delete this attendance?')"
                                            class="btn btn-danger btn-sm">

                                            Delete

                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="text-center">

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
</div>
