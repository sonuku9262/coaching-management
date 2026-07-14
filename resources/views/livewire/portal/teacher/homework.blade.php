<div class="container-fluid py-4">

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h3 class="fw-bold mb-4">📔 Homework</h3>

    @if($batches->isEmpty())

        <div class="alert alert-warning">
            Aapko abhi tak koi batch assign nahi hui hai. Administrator se sampark karein.
        </div>

    @else

        <div class="card shadow mb-4">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ $homework_id ? 'Edit Homework' : 'Assign New Homework' }}</h4>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Batch</label>
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

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Subject</label>
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

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" class="form-control" wire:model="due_date">
                        @error('due_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" wire:model="title">
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="2" wire:model="description"></textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Attachment</label>
                        <input type="file" class="form-control" wire:model="file">
                        @error('file')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        @if($old_file_name)
                            <small class="text-muted d-block">Current: {{ $old_file_name }}</small>
                        @endif
                    </div>

                </div>

                <button class="btn btn-success" wire:click="save">
                    {{ $homework_id ? 'Update Homework' : 'Assign Homework' }}
                </button>

                @if($homework_id)
                    <button class="btn btn-secondary" wire:click="resetForm">Cancel</button>
                @endif

            </div>

        </div>

        <div class="card shadow">

            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Assigned Homework</h4>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">
                            <tr>
                                <th>Title</th>
                                <th>Batch</th>
                                <th>Subject</th>
                                <th>Due Date</th>
                                <th width="160">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($homeworks as $homework)
                                <tr>
                                    <td>{{ $homework->title }}</td>
                                    <td>{{ $homework->batch?->name }}</td>
                                    <td>{{ $homework->subject?->name }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($homework->due_date)->format('d M Y') }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" wire:click="edit({{ $homework->id }})">Edit</button>
                                        <button class="btn btn-danger btn-sm" wire:click="delete({{ $homework->id }})"
                                            wire:confirm="Delete this homework?">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No Homework Assigned Yet</td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">
                    {{ $homeworks->links() }}
                </div>

            </div>

        </div>

    @endif

</div>
