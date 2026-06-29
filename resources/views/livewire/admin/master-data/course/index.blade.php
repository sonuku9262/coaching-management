<div>
    <div class="container-fluid">

        <div class="card shadow">

            <div class="card-header bg-primary text-white d-flex justify-content-between">

                <h4 class="mb-0">
                    Course Management
                </h4>

                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#courseModal">
                    + Add Course
                </button>

            </div>

            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-4">

                        <input type="text" class="form-control" placeholder="Search Course..."
                            wire:model.live="search">

                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>
                                <th>Course</th>
                                <th>Code</th>
                                <th>Duration</th>
                                <th>Fees</th>
                                <th>Status</th>
                                <th width="180">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($courses as $course)
                                <tr>

                                    <td>{{ $course->id }}</td>

                                    <td>{{ $course->name }}</td>

                                    <td>{{ $course->code }}</td>

                                    <td>{{ $course->duration }}</td>

                                    <td>₹ {{ number_format($course->fees, 2) }}</td>

                                    <td>

                                        @if ($course->status)
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

                                        <button class="btn btn-warning btn-sm" wire:click="edit({{ $course->id }})"
                                            data-bs-toggle="modal" data-bs-target="#courseModal">
                                            Edit
                                        </button>

                                        <button class="btn btn-danger btn-sm" wire:click="delete({{ $course->id }})"
                                            wire:confirm="Are you sure?">
                                            Delete
                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center">

                                        No Courses Found

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">

                    {{ $courses->links() }}

                </div>

            </div>
            @if (session()->has('success'))
                <div class="alert alert-success">

                    {{ session('success') }}

                </div>
            @endif

        </div>

    </div>

    <!-- Add Course Modal -->

    <div wire:ignore.self class="modal fade" id="courseModal" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <form wire:submit.prevent="save">

                    <div class="modal-header">

                        <h5>Add Course</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label>Course Name</label>

                                <input type="text" class="form-control" wire:model="name">

                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Course Code</label>

                                <input type="text" class="form-control" wire:model="code">

                                @error('code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Duration</label>

                                <input type="number" class="form-control" wire:model="duration">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Duration Type</label>

                                <select class="form-select" wire:model="duration_type">

                                    <option>Months</option>
                                    <option>Years</option>
                                    <option>Days</option>

                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Fees</label>

                                <input type="number" class="form-control" wire:model="fees">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Status</label>

                                <select class="form-select" wire:model="status">

                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>

                                </select>

                            </div>

                            <div class="col-md-12">

                                <label>Description</label>

                                <textarea class="form-control" rows="4" wire:model="description"></textarea>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button class="btn btn-success">

                            Save Course

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</div>
