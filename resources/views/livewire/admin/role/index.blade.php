<div>

    <div class="container-fluid py-4">

    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">Role Management</h4>

            @can('roles.create')
            <button
                class="btn btn-light"
                wire:click="resetForm"
                data-bs-toggle="modal"
                data-bs-target="#roleModal">

                + Add Role

            </button>
            @endcan

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search Role..."
                        wire:model.live="search">

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>
                            <th>Role</th>
                            <th>Permissions</th>
                            <th>Users</th>
                            <th width="180">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($roles as $role)

                            <tr>

                                <td>{{ $role->id }}</td>

                                <td>
                                    {{ $role->name }}
                                    @if($role->name === 'super-admin')
                                        <span class="badge bg-dark">All Access</span>
                                    @endif
                                </td>

                                <td>
                                    @if($role->name === 'super-admin')
                                        <span class="badge bg-dark">*</span>
                                    @else
                                        <span class="badge bg-info">{{ $role->permissions_count }}</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-secondary">{{ $role->users_count }}</span>
                                </td>

                                <td>

                                    @can('roles.edit')
                                    <button
                                        class="btn btn-warning btn-sm"
                                        wire:click="edit({{ $role->id }})"
                                        data-bs-toggle="modal"
                                        data-bs-target="#roleModal">

                                        Edit

                                    </button>
                                    @endcan

                                    @can('roles.delete')
                                    <button
                                        class="btn btn-danger btn-sm"
                                        wire:click="delete({{ $role->id }})"
                                        wire:confirm="Are you sure you want to delete this role?">

                                        Delete

                                    </button>
                                    @endcan

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center">

                                    No Roles Found

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $roles->links() }}

            </div>

        </div>

    </div>

</div>

<!-- Role Modal -->

<div
    wire:ignore.self
    class="modal fade"
    id="roleModal"
    tabindex="-1">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'save' }}">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">

                        {{ $isEdit ? 'Edit Role' : 'Add Role' }}

                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>Role Name</label>

                        <input
                            type="text"
                            class="form-control"
                            wire:model="name">

                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <hr>

                    <h6 class="fw-bold">Permissions</h6>

                    @if($isEdit && $name === 'super-admin')

                        <div class="alert alert-info mb-0">
                            Super Admin automatically has every permission.
                        </div>

                    @else

                        @foreach($permissionGroups as $module => $permissions)

                            <div class="border rounded p-2 mb-2">

                                <div class="fw-bold text-primary mb-1">{{ $module }}</div>

                                <div class="row">

                                    @foreach($permissions as $permission)

                                        <div class="col-md-3">

                                            <div class="form-check">

                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="perm-{{ $permission->id }}"
                                                    value="{{ $permission->name }}"
                                                    wire:model="selectedPermissions">

                                                <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        @endforeach

                    @endif

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

                        {{ $isEdit ? 'Update Role' : 'Save Role' }}

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



</div>
