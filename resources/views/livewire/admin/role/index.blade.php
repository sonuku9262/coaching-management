<div>


    <div class="container-fluid">

        <div class="card shadow">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <h4 class="mb-0">
                    Role Management
                </h4>

                <button class="btn btn-light">
                    + Add Role
                </button>

            </div>


            <div class="card-body">

                {{-- Add Role Form Start --}}
                <form wire:submit.prevent="save" class="mb-4">

                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">

                        <div class="col-md-3">
                            <label class="form-label">Role Name</label>

                            <input type="text" class="form-control" wire:model="name" placeholder="Enter Role Name">

                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Slug</label>

                            <input type="text" class="form-control" wire:model="slug" placeholder="Enter Slug">

                            @error('slug')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Description</label>

                            <input type="text" class="form-control" wire:model="description"
                                placeholder="Enter Description">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label d-block">&nbsp;</label>

                            @if ($isEdit)
                                <button class="btn btn-warning w-100" wire:click="update">
                                    Update Role
                                </button>
                            @else
                                <button type="submit" class="btn btn-success w-100">
                                    Save Role
                                </button>
                            @endif
                        </div>

                    </div>

                </form>
                {{-- Add Role Form End --}}

                {{-- Search --}}
                <div class="row mb-3">

                    <div class="col-md-4">

                        <input type="text" class="form-control" placeholder="Search Role..."
                            wire:model.live="search">

                    </div>

                </div>

                <div class="table-responsive">

                    <!-- Tumhara Table Yahin Rahega -->

                </div>

            </div>


            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-4">

                        <input type="text" class="form-control" placeholder="Search Role..."
                            wire:model.live="search">

                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>

                                <th>Role</th>

                                <th>Slug</th>

                                <th>Status</th>

                                <th width="180">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($roles as $role)
                                <tr>

                                    <td>{{ $role->id }}</td>

                                    <td>{{ $role->name }}</td>

                                    <td>{{ $role->slug }}</td>

                                    <td>

                                        @if ($role->status)
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

                                        <button wire:click="edit({{ $role->id }})" class="btn btn-warning btn-sm">

                                            Edit

                                        </button>

                                        <button wire:click="delete({{ $role->id }})" class="btn btn-danger btn-sm">

                                            Delete

                                        </button>

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
</div>
