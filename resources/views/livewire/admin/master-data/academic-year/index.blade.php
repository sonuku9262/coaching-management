<div>
    <div class="container-fluid">

        <div class="card shadow">

            <div class="card-header bg-primary text-white d-flex justify-content-between">

                <h4>Academic Year</h4>

                <button class="btn btn-light" wire:click="resetForm" data-bs-toggle="modal"
                    data-bs-target="#academicYearModal">

                    + Add Academic Year

                </button>

            </div>

            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-4">

                        <input type="text" class="form-control" placeholder="Search..." wire:model.live="search">

                    </div>

                </div>

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($academicYears as $year)
                            <tr>

                                <td>{{ $year->id }}</td>

                                <td>{{ $year->name }}</td>

                                <td>{{ $year->start_date }}</td>

                                <td>{{ $year->end_date }}</td>

                                <td>

                                    @if ($year->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif

                                </td>

                                <td>

                                    <button class="btn btn-warning btn-sm" wire:click="edit({{ $year->id }})"
                                        data-bs-toggle="modal" data-bs-target="#academicYearModal">

                                        Edit

                                    </button>

                                    <button class="btn btn-danger btn-sm" wire:click="delete({{ $year->id }})">

                                        Delete

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center">

                                    No Academic Year Found

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

                <div wire:ignore.self class="modal fade" id="academicYearModal">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <form wire:submit.prevent="save">

                                <div class="modal-header">

                                    <h5>

                                        {{ $academic_year_id ? 'Edit' : 'Add' }}

                                        Academic Year

                                    </h5>

                                    <button class="btn-close" data-bs-dismiss="modal">
                                    </button>

                                </div>

                                <div class="modal-body">

                                    <label>Name</label>

                                    <input type="text" class="form-control" wire:model="name">

                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <br>

                                    <label>Start Date</label>

                                    <input type="date" class="form-control" wire:model="start_date">

                                    @error('start_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <br>

                                    <label>End Date</label>

                                    <input type="date" class="form-control" wire:model="end_date">

                                    @error('end_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <br>

                                    <label>Status</label>

                                    <select class="form-select" wire:model="status">

                                        <option value="1">Active</option>

                                        <option value="0">Inactive</option>

                                    </select>

                                </div>

                                <div class="modal-footer">

                                    <button class="btn btn-success">

                                        {{ $academic_year_id ? 'Update' : 'Save' }}

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

                {{ $academicYears->links() }}

            </div>
            @if (session()->has('success'))
                <div class="alert alert-success">

                    {{ session('success') }}

                </div>
            @endif

        </div>

    </div>
</div>
