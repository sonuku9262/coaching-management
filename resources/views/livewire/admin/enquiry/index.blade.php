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

            <h4 class="mb-0">
                Admission Enquiries
                @if($newCount)
                    <span class="badge bg-warning text-dark">{{ $newCount }} New</span>
                @endif
            </h4>

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search name / mobile / email..."
                        wire:model.live="search">
                </div>

                <div class="col-md-3">
                    <select class="form-select" wire:model.live="status">
                        <option value="">All Status</option>
                        <option value="new">New</option>
                        <option value="contacted">Contacted</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Course</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th width="220">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($enquiries as $enquiry)

                            <tr>

                                <td>{{ $enquiry->id }}</td>

                                <td>{{ $enquiry->created_at->format('d M Y') }}</td>

                                <td>
                                    {{ $enquiry->name }}
                                    @if($enquiry->email)
                                        <br><small class="text-muted">{{ $enquiry->email }}</small>
                                    @endif
                                </td>

                                <td>
                                    <a href="tel:{{ $enquiry->mobile }}" class="text-decoration-none">
                                        {{ $enquiry->mobile }}
                                    </a>
                                </td>

                                <td>{{ $enquiry->course?->name ?? '-' }}</td>

                                <td style="max-width: 260px;">
                                    <small>{{ \Illuminate\Support\Str::limit($enquiry->message, 100) }}</small>
                                </td>

                                <td>
                                    <span class="badge {{ $enquiry->status === 'new' ? 'bg-warning text-dark' : ($enquiry->status === 'contacted' ? 'bg-info' : 'bg-secondary') }}">
                                        {{ ucfirst($enquiry->status) }}
                                    </span>
                                </td>

                                <td>

                                    @can('enquiries.edit')
                                        @if($enquiry->status === 'new')
                                            <button class="btn btn-info btn-sm"
                                                wire:click="markStatus({{ $enquiry->id }}, 'contacted')">
                                                ✆ Contacted
                                            </button>
                                        @endif

                                        @if($enquiry->status !== 'closed')
                                            <button class="btn btn-secondary btn-sm"
                                                wire:click="markStatus({{ $enquiry->id }}, 'closed')">
                                                Close
                                            </button>
                                        @endif
                                    @endcan

                                    @can('enquiries.delete')
                                        <button class="btn btn-danger btn-sm"
                                            wire:click="delete({{ $enquiry->id }})"
                                            wire:confirm="Delete this enquiry?">
                                            Delete
                                        </button>
                                    @endcan

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center">No Enquiries Found</td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $enquiries->links() }}
            </div>

        </div>

    </div>

</div>

</div>
