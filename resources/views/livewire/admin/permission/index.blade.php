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

            <h4 class="mb-0">Permission Management</h4>

            @can('permissions.create')
            <button
                class="btn btn-light"
                wire:click="resetForm"
                data-bs-toggle="modal"
                data-bs-target="#permissionModal">

                + Add Permission

            </button>
            @endcan

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search Permission..."
                        wire:model.live="search">

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>
                            <th>Permission</th>
                            <th>Module</th>
                            <th width="180">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($permissions as $permission)

                            <tr>

                                <td>{{ $permission->id }}</td>

                                <td><code>{{ $permission->name }}</code></td>

                                <td>{{ $permission->module }}</td>

                                <td>

                                    @can('permissions.edit')
                                    <button
                                        class="btn btn-warning btn-sm"
                                        wire:click="edit({{ $permission->id }})"
                                        data-bs-toggle="modal"
                                        data-bs-target="#permissionModal">

                                        Edit

                                    </button>
                                    @endcan

                                    @can('permissions.delete')
                                    <button
                                        class="btn btn-danger btn-sm"
                                        wire:click="delete({{ $permission->id }})"
                                        wire:confirm="Deleting a permission removes it from all roles. Continue?">

                                        Delete

                                    </button>
                                    @endcan

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="text-center">

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

</div>

<!-- Permission Modal -->

<div
    wire:ignore.self
    class="modal fade"
    id="permissionModal"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form wire:submit.prevent="save">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">
                        {{ $permission_id ? 'Edit Permission' : 'Add Permission' }}
                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>Permission Name</label>

                        <input
                            type="text"
                            class="form-control"
                            placeholder="e.g. exams.view"
                            wire:model="name">

                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        <small class="text-muted">
                            Format: <code>module.action</code> (view / create / edit / delete)
                        </small>

                    </div>

                    <div class="mb-3">

                        <label>Module (group)</label>

                        <input
                            type="text"
                            class="form-control"
                            list="moduleOptions"
                            placeholder="e.g. Examination"
                            wire:model="module">

                        <datalist id="moduleOptions">
                            @foreach($modules as $moduleOption)
                                <option value="{{ $moduleOption }}">
                            @endforeach
                        </datalist>

                        @error('module')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

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
                        {{ $permission_id ? 'Update Permission' : 'Save Permission' }}
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</div>
