<div>

    <div class="container-fluid py-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">Activity Log</h4>

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search..."
                        wire:model.live="search">
                </div>

                <div class="col-md-3">
                    <select class="form-select" wire:model.live="event">
                        <option value="">All Events</option>
                        <option value="created">Created</option>
                        <option value="updated">Updated</option>
                        <option value="deleted">Deleted</option>
                    </select>
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>When</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Record</th>
                            <th>Changes</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($activities as $activity)

                            <tr>

                                <td>{{ $activity->id }}</td>

                                <td>
                                    {{ $activity->created_at->format('d M Y H:i') }}
                                </td>

                                <td>{{ $activity->causer?->name ?? 'System' }}</td>

                                <td>
                                    <span class="badge {{ $activity->event === 'created' ? 'bg-success' : ($activity->event === 'deleted' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                        {{ $activity->event }}
                                    </span>
                                </td>

                                <td>
                                    {{ class_basename($activity->subject_type) }}
                                    #{{ $activity->subject_id }}
                                </td>

                                <td style="max-width: 420px;">

                                    @php
                                        $changes = $activity->properties['attributes'] ?? [];
                                        $old = $activity->properties['old'] ?? [];
                                    @endphp

                                    @foreach($changes as $field => $value)
                                        <small class="d-block">
                                            <strong>{{ $field }}:</strong>
                                            @if(array_key_exists($field, $old))
                                                <span class="text-danger">{{ \Illuminate\Support\Str::limit((string) $old[$field], 30) }}</span> →
                                            @endif
                                            <span class="text-success">{{ \Illuminate\Support\Str::limit((string) $value, 30) }}</span>
                                        </small>
                                    @endforeach

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center">No Activity Recorded</td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $activities->links() }}
            </div>

        </div>

    </div>

</div>

</div>
