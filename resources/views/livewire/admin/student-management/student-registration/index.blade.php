<div>
    <div class="container-fluid py-4">

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <h4 class="mb-0">
                    Student Registration
                </h4>

                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#studentModal">

                    + Add Student

                </button>

            </div>

            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-4">

                        <input type="text" class="form-control" placeholder="Search Student..."
                            wire:model.live="search">

                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>
                                <th>Admission No</th>
                                <th>Student Name</th>
                                <th>Course</th>
                                <th>Batch</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th width="180">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($students as $student)
                                <tr>

                                    <td>{{ $student->id }}</td>

                                    <td>{{ $student->admission_no }}</td>

                                    <td>{{ $student->name }}</td>

                                    <td>{{ $student->course->name ?? '-' }}</td>

                                    <td>{{ $student->batch->name ?? '-' }}</td>

                                    <td>{{ $student->mobile }}</td>

                                    <td>
                                        @if ($student->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <td>

                                        <button class="btn btn-warning btn-sm" wire:click="edit({{ $student->id }})"
                                            data-bs-toggle="modal" data-bs-target="#studentModal">

                                            Edit

                                        </button>

                                        <button class="btn btn-danger btn-sm" wire:click="delete({{ $student->id }})">

                                            Delete

                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8" class="text-center">
                                        No Students Found
                                    </td>

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

    <!-- Student Registration Modal -->

    <div wire:ignore.self class="modal fade" id="studentModal" tabindex="-1">

        <div class="modal-dialog modal-xl">

            <div class="modal-content">

                <form wire:submit.prevent="save">

                    <div class="modal-header bg-primary text-white">

                        <h5 class="modal-title">

                            {{ $student_id ? 'Edit Student' : 'Add Student' }}

                        </h5>

                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <!-- Academic Year -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">Academic Year</label>

                                <select class="form-select" wire:model="academic_year_id">

                                    <option value="">Select Academic Year</option>

                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year->id }}">
                                            {{ $year->name }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('academic_year_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <!-- Academic Session -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">Academic Session</label>

                                <select class="form-select" wire:model="academic_session_id">

                                    <option value="">Select Academic Session</option>

                                    @foreach ($academicSessions as $session)
                                        <option value="{{ $session->id }}">
                                            {{ $session->name }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('academic_session_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <!-- Course -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">Course</label>

                                <select class="form-select" wire:model="course_id">

                                    <option value="">Select Course</option>

                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">
                                            {{ $course->name }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('course_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <!-- Batch -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">Batch</label>

                                <select class="form-select" wire:model="batch_id">

                                    <option value="">Select Batch</option>

                                    @foreach ($batches as $batch)
                                        <option value="{{ $batch->id }}">
                                            {{ $batch->name }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('batch_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <!-- Classroom -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">Classroom</label>

                                <select class="form-select" wire:model="classroom_id">

                                    <option value="">Select Classroom</option>

                                    @foreach ($classrooms as $classroom)
                                        <option value="{{ $classroom->id }}">
                                            {{ $classroom->name }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('classroom_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <!-- Shift -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">Shift</label>

                                <select class="form-select" wire:model="shift_id">

                                    <option value="">Select Shift</option>

                                    @foreach ($shifts as $shift)
                                        <option value="{{ $shift->id }}">
                                            {{ $shift->name }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('shift_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <hr class="my-3">

                            <h5 class="text-primary">
                                Student Information
                            </h5>

                            <!-- Student Name -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Student Name</label>

                                <input type="text" class="form-control" wire:model="name">

                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <!-- Father Name -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Father Name</label>

                                <input type="text" class="form-control" wire:model="father_name">

                                @error('father_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <!-- Mother Name -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Mother Name</label>

                                <input type="text" class="form-control" wire:model="mother_name">

                            </div>

                            <!-- Gender -->

                            <div class="col-md-3 mb-3">

                                <label class="form-label">Gender</label>

                                <select class="form-select" wire:model="gender">

                                    <option value="Male">Male</option>

                                    <option value="Female">Female</option>

                                    <option value="Other">Other</option>

                                </select>

                            </div>

                            <!-- Date of Birth -->

                            <div class="col-md-3 mb-3">

                                <label class="form-label">Date of Birth</label>

                                <input type="date" class="form-control" wire:model="dob">

                                @error('dob')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <!-- Mobile -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Mobile Number</label>

                                <input type="text" class="form-control" wire:model="mobile">

                                @error('mobile')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <!-- Email -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Email</label>

                                <input type="email" class="form-control" wire:model="email">

                            </div>

                            <hr class="my-3">

                            <h5 class="text-primary">
                                Address & Admission Details
                            </h5>

                            <!-- Address -->

                            <div class="col-md-12 mb-3">

                                <label class="form-label">Address</label>

                                <textarea class="form-control" rows="3" wire:model="address"></textarea>

                            </div>

                            <!-- Admission Date -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">Admission Date</label>

                                <input type="date" class="form-control" wire:model="admission_date">

                                @error('admission_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <!-- Status -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">Status</label>

                                <select class="form-select" wire:model="status">

                                    <option value="1">
                                        Active
                                    </option>

                                    <option value="0">
                                        Inactive
                                    </option>

                                </select>

                            </div>

                            <!-- Photo -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">Student Photo</label>

                                <input type="file" class="form-control" wire:model="photo">

                                @error('photo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                            Close

                        </button>

                        <button type="submit" class="btn btn-primary">

                            {{ $student_id ? 'Update Student' : 'Save Student' }}

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</div>
