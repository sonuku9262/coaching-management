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

            <h4 class="mb-0">
                Subject Management
            </h4>

            <button
                class="btn btn-light"
                wire:click="resetForm"
                data-bs-toggle="modal"
                data-bs-target="#subjectModal">

                + Add Subject

            </button>

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search Subject..."
                        wire:model.live="search">

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                    <tr>

                        <th>ID</th>
                        <th>Course</th>
                        <th>Subject</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th width="180">Action</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($subjects as $subject)

                        <tr>

                            <td>{{ $subject->id }}</td>

                            <td>{{ $subject->course->name ?? '-' }}</td>

                            <td>{{ $subject->name }}</td>

                            <td>{{ $subject->code }}</td>

                            <td>

                                @if($subject->status)

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

                                <button
                                    class="btn btn-warning btn-sm"
                                    wire:click="edit({{ $subject->id }})"
                                    data-bs-toggle="modal"
                                    data-bs-target="#subjectModal">

                                    Edit

                                </button>

                                <button
                                    class="btn btn-danger btn-sm"
                                    wire:click="delete({{ $subject->id }})">

                                    Delete

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center">

                                No Subject Found

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $subjects->links() }}

            </div>

        </div>

    </div>

</div>

<!-- Modal -->

<div
    wire:ignore.self
    class="modal fade"
    id="subjectModal"
    tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form wire:submit.prevent="save">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">

                        {{ $subject_id ? 'Edit Subject' : 'Add Subject' }}

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

                            <label>Course</label>

                            <select
                                class="form-select"
                                wire:model="course_id">

                                <option value="">

                                    Select Course

                                </option>

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

                        <div class="col-md-6 mb-3">

                            <label>Subject Name</label>

                            <input
                                type="text"
                                class="form-control"
                                wire:model="name">

                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Subject Code</label>

                            <input
                                type="text"
                                class="form-control"
                                wire:model="code">

                            @error('code')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Status</label>

                            <select
                                class="form-select"
                                wire:model="status">

                                <option value="1">Active</option>

                                <option value="0">Inactive</option>

                            </select>

                        </div>

                        <div class="col-md-12">

                            <label>Description</label>

                            <textarea
                                class="form-control"
                                rows="4"
                                wire:model="description"></textarea>

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
                        class="btn btn-primary">

                        {{ $subject_id ? 'Update Subject' : 'Save Subject' }}

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
</div>
