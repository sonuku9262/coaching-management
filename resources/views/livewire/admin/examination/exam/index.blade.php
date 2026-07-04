<div>

    <div class="container-fluid py-4">

    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">Exam Management</h4>

            @can('exams.create')
            <button
                class="btn btn-light"
                wire:click="resetForm"
                data-bs-toggle="modal"
                data-bs-target="#examModal">

                + Add Exam

            </button>
            @endcan

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search Exam..."
                        wire:model.live="search">

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Exam</th>
                            <th>Course</th>
                            <th>Batch</th>
                            <th>Dates</th>
                            <th>Subjects</th>
                            <th>Status</th>
                            <th width="260">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($exams as $exam)

                            <tr>

                                <td>{{ $exam->id }}</td>

                                <td>{{ $exam->name }}</td>

                                <td>{{ $exam->course?->name }}</td>

                                <td>{{ $exam->batch?->name }}</td>

                                <td>
                                    @if($exam->start_date)
                                        {{ \Illuminate\Support\Carbon::parse($exam->start_date)->format('d M') }}
                                        —
                                        {{ $exam->end_date ? \Illuminate\Support\Carbon::parse($exam->end_date)->format('d M Y') : '' }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-info">{{ $exam->schedules_count }}</span>
                                </td>

                                <td>
                                    @if($exam->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                <td>

                                    @can('exams.edit')
                                    <button
                                        class="btn btn-primary btn-sm"
                                        wire:click="manageSchedules({{ $exam->id }})"
                                        data-bs-toggle="modal"
                                        data-bs-target="#scheduleModal">
                                        Schedules
                                    </button>

                                    <button
                                        class="btn btn-warning btn-sm"
                                        wire:click="edit({{ $exam->id }})"
                                        data-bs-toggle="modal"
                                        data-bs-target="#examModal">
                                        Edit
                                    </button>
                                    @endcan

                                    @can('exams.delete')
                                    <button
                                        class="btn btn-danger btn-sm"
                                        wire:click="delete({{ $exam->id }})"
                                        wire:confirm="Deleting an exam also deletes its schedules and results. Continue?">
                                        Delete
                                    </button>
                                    @endcan

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center">No Exams Found</td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $exams->links() }}

            </div>

        </div>

    </div>

</div>

<!-- Exam Modal -->

<div
    wire:ignore.self
    class="modal fade"
    id="examModal"
    tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form wire:submit.prevent="save">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">
                        {{ $isEdit ? 'Edit Exam' : 'Add Exam' }}
                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label>Exam Name</label>

                            <input type="text" class="form-control" placeholder="e.g. Monthly Test - July" wire:model="name">

                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Course</label>

                            <select class="form-select" wire:model.live="course_id">

                                <option value="">-- Select Course --</option>

                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach

                            </select>

                            @error('course_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Batch</label>

                            <select class="form-select" wire:model="batch_id">

                                <option value="">-- Select Batch --</option>

                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                                @endforeach

                            </select>

                            @error('batch_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-md-3 mb-3">

                            <label>Start Date</label>

                            <input type="date" class="form-control" wire:model="start_date">

                        </div>

                        <div class="col-md-3 mb-3">

                            <label>End Date</label>

                            <input type="date" class="form-control" wire:model="end_date">

                            @error('end_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <div class="form-check form-switch mt-4">

                                <input type="checkbox" class="form-check-input" id="examStatus" wire:model="status">

                                <label class="form-check-label" for="examStatus">Active</label>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? 'Update Exam' : 'Save Exam' }}
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- Schedule Modal -->

<div
    wire:ignore.self
    class="modal fade"
    id="scheduleModal"
    tabindex="-1">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title">
                    Exam Schedule
                    @if($scheduleExam)
                        — {{ $scheduleExam->name }} ({{ $scheduleExam->course?->name }} / {{ $scheduleExam->batch?->name }})
                    @endif
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                @if($scheduleExam)

                    <form wire:submit.prevent="addSchedule">

                        <div class="row align-items-end">

                            <div class="col-md-3 mb-3">

                                <label>Subject</label>

                                <select class="form-select" wire:model="subject_id">

                                    <option value="">-- Select --</option>

                                    @foreach($scheduleSubjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach

                                </select>

                                @error('subject_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="col-md-2 mb-3">

                                <label>Date</label>

                                <input type="date" class="form-control" wire:model="exam_date">

                                @error('exam_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="col-md-2 mb-3">

                                <label>Start Time</label>

                                <input type="time" class="form-control" wire:model="start_time">

                            </div>

                            <div class="col-md-2 mb-3">

                                <label>Total Marks</label>

                                <input type="number" class="form-control" wire:model="total_marks">

                                @error('total_marks')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="col-md-2 mb-3">

                                <label>Passing Marks</label>

                                <input type="number" class="form-control" wire:model="passing_marks">

                                @error('passing_marks')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="col-md-1 mb-3">

                                <button type="submit" class="btn btn-primary w-100">Add</button>

                            </div>

                        </div>

                    </form>

                    <table class="table table-bordered">

                        <thead class="table-dark">
                            <tr>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Total</th>
                                <th>Passing</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($scheduleExam->schedules as $schedule)

                                <tr>
                                    <td>{{ $schedule->subject?->name }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($schedule->exam_date)->format('d M Y') }}</td>
                                    <td>
                                        {{ $schedule->start_time ? substr($schedule->start_time, 0, 5) : '-' }}
                                        {{ $schedule->end_time ? '— ' . substr($schedule->end_time, 0, 5) : '' }}
                                    </td>
                                    <td>{{ $schedule->total_marks }}</td>
                                    <td>{{ $schedule->passing_marks }}</td>
                                    <td>
                                        <button
                                            class="btn btn-danger btn-sm"
                                            wire:click="deleteSchedule({{ $schedule->id }})"
                                            wire:confirm="Remove this subject from the exam?">
                                            Remove
                                        </button>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="text-center">No Subjects Scheduled</td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                @endif

            </div>

        </div>

    </div>

</div>

</div>
