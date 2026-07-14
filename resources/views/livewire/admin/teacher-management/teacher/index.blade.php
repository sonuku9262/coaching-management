<div>
    <div class="container-fluid py-4">

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Teacher Management</h4>

                <button class="btn btn-light" wire:click="resetForm" data-bs-toggle="modal" data-bs-target="#teacherModal">

                    + Add Teacher

                </button>

            </div>

            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-4">

                        <input type="text" class="form-control" placeholder="Search Teacher..."
                            wire:model.live="search">

                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>
                                <th>Photo</th>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Joining Date</th>
                                <th>Status</th>
                                <th width="180">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($teachers as $teacher)
                                <tr>

                                    <td>{{ $teacher->id }}</td>

                                    <td>
                                        @if ($teacher->photo)
                                            <img src="{{ asset('storage/' . $teacher->photo) }}" width="45"
                                                height="45" class="rounded-circle">
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>{{ $teacher->employee_id }}</td>

                                    <td>{{ $teacher->name }}</td>

                                    <td>{{ $teacher->mobile }}</td>

                                    <td>{{ $teacher->joining_date }}</td>

                                    <td>

                                        @if ($teacher->status)
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        <button class="btn btn-warning btn-sm" wire:click="edit({{ $teacher->id }})"
                                            data-bs-toggle="modal" data-bs-target="#teacherModal">

                                            Edit

                                        </button>

                                        <button class="btn btn-danger btn-sm" wire:click="delete({{ $teacher->id }})">

                                            Delete

                                        </button>

                                        @can('teachers.edit')
                                            <button class="btn btn-secondary btn-sm" wire:click="manageAssignments({{ $teacher->id }})"
                                                data-bs-toggle="modal" data-bs-target="#assignModal">

                                                Assign Subjects

                                            </button>
                                        @endcan

                                        @can('users.create')
                                            @if ($teacher->user_id)
                                                <span class="badge bg-success">Login ✓</span>
                                            @else
                                                <button class="btn btn-info btn-sm mt-1"
                                                    wire:click="createLogin({{ $teacher->id }})"
                                                    wire:confirm="Create a portal login for this teacher? Default password will be their mobile number.">
                                                    Create Login
                                                </button>
                                            @endif
                                        @endcan

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8" class="text-center">

                                        No Teachers Found

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">

                    {{ $teachers->links() }}

                </div>

            </div>

        </div>

        <!-- Teacher Modal -->

        <div wire:ignore.self class="modal fade" id="teacherModal" tabindex="-1">

            <div class="modal-dialog modal-lg">

                <div class="modal-content">

                    <form wire:submit.prevent="save">

                        <div class="modal-header bg-primary text-white">

                            <h5 class="modal-title">

                                {{ $teacher_id ? 'Edit Teacher' : 'Add Teacher' }}

                            </h5>

                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label>Employee ID</label>

                                    <input type="text" class="form-control" wire:model="employee_id">

                                    @error('employee_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Name</label>

                                    <input type="text" class="form-control" wire:model="name">

                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Mobile</label>

                                    <input type="text" class="form-control" wire:model="mobile">

                                    @error('mobile')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Email</label>

                                    <input type="email" class="form-control" wire:model="email">

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Qualification</label>

                                    <input type="text" class="form-control" wire:model="qualification">

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Experience</label>

                                    <input type="text" class="form-control" wire:model="experience">

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Joining Date</label>

                                    <input type="date" class="form-control" wire:model="joining_date">

                                    @error('joining_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Salary</label>

                                    <input type="number" class="form-control" wire:model="salary">

                                    @error('salary')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Photo</label>

                                    <input type="file" class="form-control" wire:model="photo">

                                    @if ($old_photo)
                                        <img src="{{ asset('storage/' . $old_photo) }}" width="70"
                                            class="mt-2 rounded">
                                    @endif

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Status</label>

                                    <select class="form-select" wire:model="status">

                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>

                                    </select>

                                </div>

                                <div class="col-md-12 mb-3">

                                    <label>Address</label>

                                    <textarea class="form-control" rows="3" wire:model="address"></textarea>

                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                Close

                            </button>

                            <button type="submit" class="btn btn-primary">

                                {{ $teacher_id ? 'Update Teacher' : 'Save Teacher' }}

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- Assign Subjects Modal -->

        <div wire:ignore.self class="modal fade" id="assignModal" tabindex="-1">

            <div class="modal-dialog modal-lg">

                <div class="modal-content">

                    <div class="modal-header bg-secondary text-white">

                        <h5 class="modal-title">
                            Assign Subjects — {{ $assign_teacher_name }}
                        </h5>

                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="row align-items-end mb-3">

                            <div class="col-md-4 mb-2">

                                <label>Course</label>

                                <select class="form-select" wire:model.live="assign_course_id">
                                    <option value="">Select Course</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-md-3 mb-2">

                                <label>Batch</label>

                                <select class="form-select" wire:model="assign_batch_id">
                                    <option value="">Select Batch</option>
                                    @foreach ($assignBatches as $batch)
                                        <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                                    @endforeach
                                </select>
                                @error('assign_batch_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="col-md-3 mb-2">

                                <label>Subject</label>

                                <select class="form-select" wire:model="assign_subject_id">
                                    <option value="">Select Subject</option>
                                    @foreach ($assignSubjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('assign_subject_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="col-md-2 mb-2">

                                <button type="button" class="btn btn-primary w-100" wire:click="addAssignment">
                                    + Add
                                </button>

                            </div>

                        </div>

                        <div class="table-responsive">

                            <table class="table table-bordered table-sm mb-0">

                                <thead class="table-dark">
                                    <tr>
                                        <th>Batch</th>
                                        <th>Subject</th>
                                        <th width="90">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($currentAssignments as $assignment)
                                        <tr>
                                            <td>{{ $assignment->batch?->name }}</td>
                                            <td>{{ $assignment->subject?->name }}</td>
                                            <td>
                                                <button class="btn btn-danger btn-sm"
                                                    wire:click="removeAssignment({{ $assignment->id }})"
                                                    wire:confirm="Remove this assignment?">
                                                    Remove
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No Subjects Assigned Yet</td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
