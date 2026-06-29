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

            <h4 class="mb-0">Batch Management</h4>

            <button
                class="btn btn-light"
                wire:click="resetForm"
                data-bs-toggle="modal"
                data-bs-target="#batchModal">

                + Add Batch

            </button>

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search Batch..."
                        wire:model.live="search">

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                    <tr>

                        <th>ID</th>
                        <th>Academic Year</th>
                        <th>Academic Session</th>
                        <th>Course</th>
                        <th>Batch</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th width="180">Action</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($batches as $batch)

                        <tr>

                            <td>{{ $batch->id }}</td>

                            <td>{{ $batch->academicYear->name ?? '-' }}</td>

                            <td>{{ $batch->academicSession->name ?? '-' }}</td>

                            <td>{{ $batch->course->name ?? '-' }}</td>

                            <td>{{ $batch->name }}</td>

                            <td>{{ $batch->capacity }}</td>

                            <td>
                                @if($batch->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>

                            <td>

                                <button
                                    class="btn btn-warning btn-sm"
                                    wire:click="edit({{ $batch->id }})"
                                    data-bs-toggle="modal"
                                    data-bs-target="#batchModal">

                                    Edit

                                </button>

                                <button
                                    class="btn btn-danger btn-sm"
                                    wire:click="delete({{ $batch->id }})">

                                    Delete

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center">

                                No Batch Found

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $batches->links() }}

            </div>

        </div>

    </div>

</div>


<!-- Batch Modal -->

<div
    wire:ignore.self
    class="modal fade"
    id="batchModal"
    tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form wire:submit.prevent="save">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">
                        {{ $batch_id ? 'Edit Batch' : 'Add Batch' }}
                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <!-- Academic Year -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Academic Year</label>

                            <select
                                class="form-select"
                                wire:model="academic_year_id">

                                <option value="">Select Academic Year</option>

                                @foreach($academicYears as $year)
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

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Academic Session</label>

                            <select
                                class="form-select"
                                wire:model="academic_session_id">

                                <option value="">Select Academic Session</option>

                                @foreach($academicSessions as $session)

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

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Course</label>

                            <select
                                class="form-select"
                                wire:model="course_id">

                                <option value="">Select Course</option>

                                @foreach($courses as $course)

                                    <option value="{{ $course->id }}">
                                        {{ $course->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('course_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <!-- Batch Name -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Batch Name</label>

                            <input
                                type="text"
                                class="form-control"
                                wire:model="name">

                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <!-- Start Date -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Start Date</label>

                            <input
                                type="date"
                                class="form-control"
                                wire:model="start_date">

                            @error('start_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <!-- End Date -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">End Date</label>

                            <input
                                type="date"
                                class="form-control"
                                wire:model="end_date">

                            @error('end_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <!-- Capacity -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Capacity</label>

                            <input
                                type="number"
                                class="form-control"
                                wire:model="capacity">

                            @error('capacity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <!-- Status -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Status</label>

                            <select
                                class="form-select"
                                wire:model="status">

                                <option value="1">Active</option>
                                <option value="0">Inactive</option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Close

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        {{ $batch_id ? 'Update Batch' : 'Save Batch' }}

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
    


</div>
