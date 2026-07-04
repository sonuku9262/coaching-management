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

            <h4 class="mb-0">Website Gallery</h4>

            @can('gallery.create')
            <button class="btn btn-light" wire:click="resetForm"
                data-bs-toggle="modal" data-bs-target="#galleryModal">
                + Add Photo
            </button>
            @endcan

        </div>

        <div class="card-body">

            <div class="row g-4">

                @forelse($galleries as $gallery)

                    <div class="col-lg-3 col-md-4 col-6">

                        <div class="card shadow h-100">

                            <img src="{{ asset('storage/' . $gallery->image) }}"
                                class="card-img-top" style="height: 160px; object-fit: cover;">

                            <div class="card-body p-2">

                                <p class="mb-1 fw-semibold small">{{ $gallery->title ?: '—' }}</p>

                                <div class="d-flex justify-content-between align-items-center">

                                    @if($gallery->status)
                                        <span class="badge bg-success">Visible</span>
                                    @else
                                        <span class="badge bg-secondary">Hidden</span>
                                    @endif

                                    <div>

                                        @can('gallery.edit')
                                        <button class="btn btn-warning btn-sm"
                                            wire:click="edit({{ $gallery->id }})"
                                            data-bs-toggle="modal" data-bs-target="#galleryModal">
                                            ✏
                                        </button>
                                        @endcan

                                        @can('gallery.delete')
                                        <button class="btn btn-danger btn-sm"
                                            wire:click="delete({{ $gallery->id }})"
                                            wire:confirm="Delete this photo?">
                                            🗑
                                        </button>
                                        @endcan

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-12 text-center text-muted py-5">
                        No Photos Yet — "+ Add Photo" se website gallery banayein.
                    </div>

                @endforelse

            </div>

            <div class="mt-3">
                {{ $galleries->links() }}
            </div>

        </div>

    </div>

</div>

<!-- Gallery Modal -->

<div wire:ignore.self class="modal fade" id="galleryModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form wire:submit.prevent="save">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">{{ $isEdit ? 'Edit Photo' : 'Add Photo' }}</h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>Title (optional)</label>

                        <input type="text" class="form-control" wire:model="title">

                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label>Photo {{ $isEdit ? '(leave empty to keep current)' : '' }}</label>

                        <input type="file" class="form-control" wire:model="image">

                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        <div wire:loading wire:target="image" class="text-muted mt-1">Uploading...</div>

                        @if($old_image && ! $image)
                            <img src="{{ asset('storage/' . $old_image) }}" height="70" class="mt-2 rounded border">
                        @endif

                    </div>

                    <div class="mb-3">

                        <label>Sort Order</label>

                        <input type="number" class="form-control" wire:model="sort_order">

                    </div>

                    <div class="form-check form-switch mb-3">

                        <input type="checkbox" class="form-check-input" id="galleryStatus" wire:model="status">

                        <label class="form-check-label" for="galleryStatus">Show on Website</label>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? 'Update Photo' : 'Save Photo' }}
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</div>
