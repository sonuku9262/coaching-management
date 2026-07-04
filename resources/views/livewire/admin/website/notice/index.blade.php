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

            <h4 class="mb-0">Notices / Announcements</h4>

            @can('notices.create')
            <button class="btn btn-light" wire:click="resetForm"
                data-bs-toggle="modal" data-bs-target="#noticeModal">
                + Add Notice
            </button>
            @endcan

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search Notice..."
                        wire:model.live="search">
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Website</th>
                            <th>Status</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($notices as $notice)

                            <tr>

                                <td>{{ $notice->id }}</td>

                                <td>{{ \Illuminate\Support\Carbon::parse($notice->notice_date)->format('d M Y') }}</td>

                                <td class="fw-semibold">{{ $notice->title }}</td>

                                <td style="max-width: 280px;">
                                    <small>{{ \Illuminate\Support\Str::limit($notice->description, 90) }}</small>
                                </td>

                                <td>
                                    @if($notice->show_on_website)
                                        <span class="badge bg-info">On Website</span>
                                    @else
                                        <span class="badge bg-secondary">Internal</span>
                                    @endif
                                </td>

                                <td>
                                    @if($notice->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                <td>

                                    @can('notices.edit')
                                    <button class="btn btn-warning btn-sm"
                                        wire:click="edit({{ $notice->id }})"
                                        data-bs-toggle="modal" data-bs-target="#noticeModal">
                                        Edit
                                    </button>
                                    @endcan

                                    @can('notices.delete')
                                    <button class="btn btn-danger btn-sm"
                                        wire:click="delete({{ $notice->id }})"
                                        wire:confirm="Delete this notice?">
                                        Delete
                                    </button>
                                    @endcan

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center">No Notices Found</td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $notices->links() }}
            </div>

        </div>

    </div>

</div>

<!-- Notice Modal -->

<div wire:ignore.self class="modal fade" id="noticeModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form wire:submit.prevent="save">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">{{ $isEdit ? 'Edit Notice' : 'Add Notice' }}</h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-8 mb-3">

                            <label>Title</label>

                            <input type="text" class="form-control" wire:model="title">

                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>Notice Date</label>

                            <input type="date" class="form-control" wire:model="notice_date">

                            @error('notice_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-12 mb-3">

                            <label>Description</label>

                            <textarea class="form-control" rows="3" wire:model="description"></textarea>

                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-md-6">

                            <div class="form-check form-switch">

                                <input type="checkbox" class="form-check-input" id="noticeWebsite" wire:model="show_on_website">

                                <label class="form-check-label" for="noticeWebsite">Show on Website</label>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-check form-switch">

                                <input type="checkbox" class="form-check-input" id="noticeStatus" wire:model="status">

                                <label class="form-check-label" for="noticeStatus">Active</label>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? 'Update Notice' : 'Save Notice' }}
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</div>
