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
                Classroom Management
            </h4>

            <button
                class="btn btn-light"
                wire:click="resetForm"
                data-bs-toggle="modal"
                data-bs-target="#classroomModal">

                + Add Classroom

            </button>

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search Classroom..."
                        wire:model.live="search">

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>

                            <th>Classroom</th>

                            <th>Room No</th>

                            <th>Floor</th>

                            <th>Capacity</th>

                            <th>Status</th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($classrooms as $classroom)

                            <tr>

                                <td>{{ $classroom->id }}</td>

                                <td>{{ $classroom->name }}</td>

                                <td>{{ $classroom->room_no }}</td>

                                <td>{{ $classroom->floor }}</td>

                                <td>{{ $classroom->capacity }}</td>

                                <td>

                                    @if($classroom->status)

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
                                        wire:click="edit({{ $classroom->id }})"
                                        data-bs-toggle="modal"
                                        data-bs-target="#classroomModal">

                                        Edit

                                    </button>

                                    <button
                                        class="btn btn-danger btn-sm"
                                        wire:click="delete({{ $classroom->id }})">

                                        Delete

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center">

                                    No Classroom Found

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $classrooms->links() }}

            </div>

        </div>

    </div>

</div>

<!-- Classroom Modal -->

<div
    wire:ignore.self
    class="modal fade"
    id="classroomModal"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form wire:submit.prevent="save">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">

                        {{ $classroom_id ? 'Edit Classroom' : 'Add Classroom' }}

                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>Classroom Name</label>

                        <input
                            type="text"
                            class="form-control"
                            wire:model="name">

                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label>Room No</label>

                        <input
                            type="text"
                            class="form-control"
                            wire:model="room_no">

                        @error('room_no')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label>Floor</label>

                        <input
                            type="text"
                            class="form-control"
                            wire:model="floor">

                    </div>

                    <div class="mb-3">

                        <label>Capacity</label>

                        <input
                            type="number"
                            class="form-control"
                            wire:model="capacity">

                        @error('capacity')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label>Status</label>

                        <select
                            class="form-select"
                            wire:model="status">

                            <option value="1">
                                Active
                            </option>

                            <option value="0">
                                Inactive
                            </option>

                        </select>

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

                        {{ $classroom_id ? 'Update Classroom' : 'Save Classroom' }}

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
</div>
