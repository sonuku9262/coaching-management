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

            <h4 class="mb-0">Expense Category Management</h4>

            <button class="btn btn-light" wire:click="resetForm" data-bs-toggle="modal" data-bs-target="#expenseCategoryModal">
                + Add Category
            </button>

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search Category..." wire:model.live="search">
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->code }}</td>
                                <td>{{ $category->description }}</td>
                                <td>
                                    @if($category->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" wire:click="edit({{ $category->id }})"
                                        data-bs-toggle="modal" data-bs-target="#expenseCategoryModal">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" wire:click="delete({{ $category->id }})"
                                        wire:confirm="Delete this expense category?">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No Expense Category Found</td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $categories->links() }}
            </div>

        </div>

    </div>

</div>

<!-- Modal -->

<div wire:ignore.self class="modal fade" id="expenseCategoryModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form wire:submit.prevent="save">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        {{ $expense_category_id ? 'Edit Category' : 'Add Category' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" class="form-control" wire:model="name">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Code</label>
                        <input type="text" class="form-control" wire:model="code">
                        @error('code')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea class="form-control" rows="3" wire:model="description"></textarea>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        {{ $expense_category_id ? 'Update Category' : 'Save Category' }}
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>
</div>
