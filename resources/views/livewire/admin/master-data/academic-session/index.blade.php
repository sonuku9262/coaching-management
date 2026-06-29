<div>

    <div class="container-fluid py-4">

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                Academic Session Management
            </h4>

            <button
                class="btn btn-light"
                wire:click="resetForm"
                data-bs-toggle="modal"
                data-bs-target="#sessionModal">

                + Add Academic Session

            </button>

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search Session..."
                        wire:model.live="search">

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                    <tr>

                        <th>ID</th>

                        <th>Academic Year</th>

                        <th>Session</th>

                        <th>Start Date</th>

                        <th>End Date</th>

                        <th>Status</th>

                        <th width="170">Action</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($sessions as $session)

                        <tr>

                            <td>{{ $session->id }}</td>

                            <td>{{ $session->academicYear->name ?? '-' }}</td>

                            <td>{{ $session->name }}</td>

                            <td>{{ $session->start_date }}</td>

                            <td>{{ $session->end_date }}</td>

                            <td>

                                @if($session->status)

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
                                    wire:click="edit({{ $session->id }})"
                                    data-bs-toggle="modal"
                                    data-bs-target="#sessionModal">

                                    Edit

                                </button>

                                <button
                                    class="btn btn-danger btn-sm"
                                    onclick="confirm('Delete this record?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $session->id }})">

                                    Delete

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center">

                                No Academic Session Found

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $sessions->links() }}

            </div>

        </div>

    </div>

</div>

<!-- Modal -->

<div
    wire:ignore.self
    class="modal fade"
    id="sessionModal"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form wire:submit.prevent="save">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">

                        {{ $academic_session_id ? 'Edit Academic Session' : 'Add Academic Session' }}

                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Academic Year
                        </label>

                        <select
                            class="form-select"
                            wire:model="academic_year_id">

                            <option value="">
                                Select Academic Year
                            </option>

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

                    <div class="mb-3">

                        <label class="form-label">
                            Session Name
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            wire:model="name">

                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Start Date
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            wire:model="start_date">

                        @error('start_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            End Date
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            wire:model="end_date">

                        @error('end_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Status
                        </label>

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
                        type="submit"
                        class="btn btn-primary">

                        {{ $academic_session_id ? 'Update' : 'Save' }}

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


</div>