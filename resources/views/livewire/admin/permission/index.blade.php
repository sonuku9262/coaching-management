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

                <h4 class="mb-0">Permission Management</h4>

                <button class="btn btn-light" wire:click="resetForm" data-bs-toggle="modal"
                    data-bs-target="#permissionModal">

                    + Add Permission

                </button>

            </div>

            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-4">

                        <input type="text" class="form-control" placeholder="Search Permission..."
                            wire:model.live="search">

                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Module</th>
                                <th>Status</th>
                                <th width="180">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($permissions as $permission)
                                <tr>

                                    <td>{{ $permission->id }}</td>

                                    <td>{{ $permission->name }}</td>

                                    <td>{{ $permission->slug }}</td>

                                    <td>{{ $permission->module }}</td>

                                    <td>

                                        @if ($permission->status)
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

                                        <button class="btn btn-warning btn-sm" wire:click="edit({{ $permission->id }})"
                                            data-bs-toggle="modal" data-bs-target="#permissionModal">

                                            Edit

                                        </button>

                                        <button class="btn btn-danger btn-sm"
                                            wire:click="delete({{ $permission->id }})">

                                            Delete

                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center">

                                        No Permissions Found

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">

                    {{ $permissions->links() }}

                </div>

            </div>

        </div>

        <!-- Permission Modal -->

        <div wire:ignore.self class="modal fade" id="permissionModal" tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form wire:submit.prevent="save">

                        <div class="modal-header bg-primary text-white">

                            <h5 class="modal-title">

                                {{ $permission_id ? 'Edit Permission' : 'Add Permission' }}

                            </h5>

                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label>Permission Name</label>

                                <input type="text" class="form-control" wire:model="name"
                                    placeholder="Enter Permission Name">

                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="mb-3">

                                <label>Slug</label>

                                <input type="text" class="form-control" wire:model="slug" placeholder="view-student">

                                @error('slug')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="mb-3">

                                <label>Module</label>

                                <select class="form-select" wire:model="module">

                                    <option value="">Select Module</option>

                                    <option value="Dashboard">Dashboard</option>
                                    <option value="User Management">User Management</option>
                                    <option value="Master Data">Master Data</option>
                                    <option value="Student Management">Student Management</option>
                                    <option value="Teacher Management">Teacher Management</option>
                                    <option value="Fee Management">Fee Management</option>
                                    <option value="Attendance">Attendance</option>
                                    <option value="Examination">Examination</option>
                                    <option value="Reports">Reports</option>
                                    <option value="Settings">Settings</option>

                                </select>

                                @error('module')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="mb-3">

                                <label>Status</label>

                                <select class="form-select" wire:model="status">

                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>

                                </select>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                Close

                            </button>

                            <button type="submit" class="btn btn-primary">

                                {{ $permission_id ? 'Update Permission' : 'Save Permission' }}

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
</div>
