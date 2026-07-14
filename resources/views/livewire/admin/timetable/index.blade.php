<div class="container-fluid py-4">

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Class Timetable</h4>
        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">

                    <label class="form-label">Batch</label>

                    <select class="form-select" wire:model.live="batch_id">
                        <option value="">Select Batch</option>
                        @foreach ($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                        @endforeach
                    </select>

                </div>

            </div>

            @if($batch_id)

                <button class="btn btn-success mb-3" wire:click="resetForm" data-bs-toggle="modal" data-bs-target="#timetableModal">
                    + Add Slot
                </button>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">
                            <tr>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Classroom</th>
                                <th width="160">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($slots as $slot)
                                <tr>
                                    <td>{{ $slot->day_of_week }}</td>
                                    <td>{{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}</td>
                                    <td>{{ $slot->subject?->name }}</td>
                                    <td>{{ $slot->teacher?->name ?? '-' }}</td>
                                    <td>{{ $slot->classroom?->name ?? '-' }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" wire:click="edit({{ $slot->id }})"
                                            data-bs-toggle="modal" data-bs-target="#timetableModal">
                                            Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm" wire:click="delete({{ $slot->id }})"
                                            wire:confirm="Delete this timetable slot?">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No Slots Scheduled Yet</td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            @else

                <div class="alert alert-info mb-0">Select a batch to view or manage its timetable.</div>

            @endif

        </div>

    </div>

    <!-- Timetable Modal -->

    <div wire:ignore.self class="modal fade" id="timetableModal" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <form wire:submit.prevent="save">

                    <div class="modal-header bg-primary text-white">

                        <h5 class="modal-title">
                            {{ $timetable_id ? 'Edit Slot' : 'Add Slot' }}
                        </h5>

                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label>Subject</label>

                                <select class="form-select" wire:model.live="subject_id">
                                    <option value="">Select Subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Teacher</label>

                                <select class="form-select" wire:model="teacher_id">
                                    <option value="">Select Teacher</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>

                                @if($subject_id && $teachers->isEmpty())
                                    <small class="text-muted">No teacher assigned to this batch/subject yet.</small>
                                @endif

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Day</label>

                                <select class="form-select" wire:model="day_of_week">
                                    <option value="">Select Day</option>
                                    @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                        <option value="{{ $day }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                                @error('day_of_week')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Classroom</label>

                                <select class="form-select" wire:model="classroom_id">
                                    <option value="">Select Classroom</option>
                                    @foreach ($classrooms as $classroom)
                                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Start Time</label>

                                <input type="time" class="form-control" wire:model="start_time">
                                @error('start_time')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>End Time</label>

                                <input type="time" class="form-control" wire:model="end_time">
                                @error('end_time')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        <button type="submit" class="btn btn-primary">
                            {{ $timetable_id ? 'Update Slot' : 'Save Slot' }}
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
