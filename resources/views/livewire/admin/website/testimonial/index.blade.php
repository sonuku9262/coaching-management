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

            <h4 class="mb-0">Testimonials</h4>

            @can('testimonials.create')
            <button class="btn btn-light" wire:click="resetForm"
                data-bs-toggle="modal" data-bs-target="#testimonialModal">
                + Add Testimonial
            </button>
            @endcan

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Message</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($testimonials as $testimonial)

                            <tr>

                                <td>{{ $testimonial->id }}</td>

                                <td>
                                    @if($testimonial->photo)
                                        <img src="{{ asset('storage/' . $testimonial->photo) }}"
                                            width="45" height="45" class="rounded-circle" style="object-fit: cover;">
                                    @else
                                        👤
                                    @endif
                                </td>

                                <td>{{ $testimonial->name }}</td>

                                <td>{{ $testimonial->designation }}</td>

                                <td style="max-width: 280px;">
                                    <small>{{ \Illuminate\Support\Str::limit($testimonial->message, 90) }}</small>
                                </td>

                                <td>
                                    <span class="text-warning">
                                        {{ str_repeat('★', $testimonial->rating) }}{{ str_repeat('☆', 5 - $testimonial->rating) }}
                                    </span>
                                </td>

                                <td>
                                    @if($testimonial->status)
                                        <span class="badge bg-success">Visible</span>
                                    @else
                                        <span class="badge bg-secondary">Hidden</span>
                                    @endif
                                </td>

                                <td>

                                    @can('testimonials.edit')
                                    <button class="btn btn-warning btn-sm"
                                        wire:click="edit({{ $testimonial->id }})"
                                        data-bs-toggle="modal" data-bs-target="#testimonialModal">
                                        Edit
                                    </button>
                                    @endcan

                                    @can('testimonials.delete')
                                    <button class="btn btn-danger btn-sm"
                                        wire:click="delete({{ $testimonial->id }})"
                                        wire:confirm="Delete this testimonial?">
                                        Delete
                                    </button>
                                    @endcan

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center">No Testimonials Yet</td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $testimonials->links() }}
            </div>

        </div>

    </div>

</div>

<!-- Testimonial Modal -->

<div wire:ignore.self class="modal fade" id="testimonialModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form wire:submit.prevent="save">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">{{ $isEdit ? 'Edit Testimonial' : 'Add Testimonial' }}</h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label>Name</label>

                            <input type="text" class="form-control" wire:model="name">

                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Designation</label>

                            <input type="text" class="form-control"
                                placeholder="e.g. BCA Student / Parent" wire:model="designation">

                            @error('designation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Photo (optional)</label>

                            <input type="file" class="form-control" wire:model="photo">

                            @error('photo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            <div wire:loading wire:target="photo" class="text-muted mt-1">Uploading...</div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Rating</label>

                            <select class="form-select" wire:model="rating">
                                <option value="5">★★★★★ (5)</option>
                                <option value="4">★★★★☆ (4)</option>
                                <option value="3">★★★☆☆ (3)</option>
                                <option value="2">★★☆☆☆ (2)</option>
                                <option value="1">★☆☆☆☆ (1)</option>
                            </select>

                            @error('rating')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-12 mb-3">

                            <label>Message</label>

                            <textarea class="form-control" rows="3" wire:model="message"></textarea>

                            @error('message')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-12">

                            <div class="form-check form-switch">

                                <input type="checkbox" class="form-check-input" id="testimonialStatus" wire:model="status">

                                <label class="form-check-label" for="testimonialStatus">Show on Website</label>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? 'Update' : 'Save' }}
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</div>
