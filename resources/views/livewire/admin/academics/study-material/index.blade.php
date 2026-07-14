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

            <h4 class="mb-0">Study Material</h4>

            <button class="btn btn-light" wire:click="resetForm" data-bs-toggle="modal" data-bs-target="#studyMaterialModal">
                + Add Material
            </button>

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search Title..." wire:model.live="search">
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Title</th>
                            <th>Batch</th>
                            <th>Subject</th>
                            <th>Uploaded By</th>
                            <th>File</th>
                            <th>Status</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($materials as $material)
                            <tr>
                                <td>{{ $material->title }}</td>
                                <td>{{ $material->batch?->name }}</td>
                                <td>{{ $material->subject?->name }}</td>
                                <td>{{ $material->teacher?->name ?? 'Admin' }}</td>
                                <td>
                                    @if($material->file_path)
                                        <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank">{{ $material->file_name }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($material->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" wire:click="edit({{ $material->id }})"
                                        data-bs-toggle="modal" data-bs-target="#studyMaterialModal">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" wire:click="delete({{ $material->id }})"
                                        wire:confirm="Delete this study material?">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No Study Material Found</td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $materials->links() }}
            </div>

        </div>

    </div>

</div>

<!-- Modal -->

<div wire:ignore.self class="modal fade" id="studyMaterialModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form wire:submit.prevent="save">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        {{ $study_material_id ? 'Edit Study Material' : 'Add Study Material' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Batch</label>
                        <select class="form-select" wire:model.live="batch_id">
                            <option value="">Select Batch</option>
                            @foreach ($batches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                            @endforeach
                        </select>
                        @error('batch_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Subject</label>
                        <select class="form-select" wire:model="subject_id">
                            <option value="">Select Subject</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" class="form-control" wire:model="title">
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea class="form-control" rows="3" wire:model="description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>File</label>
                        <input type="file" class="form-control" wire:model="file">
                        @error('file')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        @if($old_file_name)
                            <small class="text-muted">Current file: {{ $old_file_name }}</small>
                        @endif
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
                        {{ $study_material_id ? 'Update Material' : 'Save Material' }}
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>
</div>
