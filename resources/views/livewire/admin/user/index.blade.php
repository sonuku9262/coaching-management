<div>
    <div class="container-fluid">

    <div class="card">

        <div class="card-header bg-primary text-white">

            <h4>User Management</h4>

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

            <table class="table table-bordered">

                <thead>

                <tr>

                    <th>ID</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th>Role</th>

                    <th>Status</th>

                </tr>

                </thead>

                <tbody>

                @forelse($users as $user)

                    <tr>

                        <td>{{ $user->id }}</td>

                        <td>{{ $user->name }}</td>

                        <td>{{ $user->email }}</td>

                        <td>{{ $user->role?->name ?? '-' }}</td>

                        <td>
                            {{ $user->status ? 'Active' : 'Inactive' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="text-center">
                            No Users Found
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

            {{ $users->links() }}

        </div>

    </div>

</div>
</div>
