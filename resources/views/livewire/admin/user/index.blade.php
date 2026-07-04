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

            <h4 class="mb-0">User Management</h4>

            @can('users.create')
            <button
                class="btn btn-light"
                wire:click="resetForm"
                data-bs-toggle="modal"
                data-bs-target="#userModal">

                + Add User

            </button>
            @endcan

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control"
                        wire:model.live="search"
                        placeholder="Search User">

                </div>

            </div>

            <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">

                <tr>

                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th width="180">Action</th>

                </tr>

                </thead>

                <tbody>

                @forelse($users as $user)

                    <tr>

                        <td>{{ $user->id }}</td>

                        <td>{{ $user->name }}</td>

                        <td>{{ $user->email }}</td>

                        <td>
                            @forelse($user->roles as $userRole)
                                <span class="badge bg-primary">{{ $userRole->name }}</span>
                            @empty
                                <span class="badge bg-secondary">No Role</span>
                            @endforelse
                        </td>

                        <td>
                            @if($user->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>

                        <td>

                            @can('users.edit')
                            <button
                                class="btn btn-warning btn-sm"
                                wire:click="edit({{ $user->id }})"
                                data-bs-toggle="modal"
                                data-bs-target="#userModal">

                                Edit

                            </button>
                            @endcan

                            @can('users.delete')
                            <button
                                class="btn btn-danger btn-sm"
                                wire:click="delete({{ $user->id }})"
                                wire:confirm="Are you sure you want to delete this user?">

                                Delete

                            </button>
                            @endcan

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center">
                            No Users Found
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

            </div>

            {{ $users->links() }}

        </div>

    </div>

</div>

<!-- User Modal -->

<div
    wire:ignore.self
    class="modal fade"
    id="userModal"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'save' }}">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">
                        {{ $isEdit ? 'Edit User' : 'Add User' }}
                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>Name</label>

                        <input
                            type="text"
                            class="form-control"
                            wire:model="name">

                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label>Email</label>

                        <input
                            type="email"
                            class="form-control"
                            wire:model="email">

                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label>Password {{ $isEdit ? '(leave blank to keep current)' : '' }}</label>

                        <input
                            type="password"
                            class="form-control"
                            wire:model="password">

                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label>Confirm Password</label>

                        <input
                            type="password"
                            class="form-control"
                            wire:model="password_confirmation">

                    </div>

                    <div class="mb-3">

                        <label>Role</label>

                        <select class="form-select" wire:model="role">

                            <option value="">-- Select Role --</option>

                            @foreach($roles as $roleOption)
                                <option value="{{ $roleOption->name }}">{{ $roleOption->name }}</option>
                            @endforeach

                        </select>

                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="form-check form-switch mb-3">

                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="userStatus"
                            wire:model="status">

                        <label class="form-check-label" for="userStatus">
                            Active
                        </label>

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
                        {{ $isEdit ? 'Update User' : 'Save User' }}
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</div>
