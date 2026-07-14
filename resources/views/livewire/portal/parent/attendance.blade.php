<div>
    <div class="container-fluid py-4">

        <h3 class="fw-bold mb-4">📋 Child Attendance</h3>

        @if($children->isEmpty())

            <div class="alert alert-warning">
                No student is linked to your account yet. Please contact the administrator.
            </div>

        @else

            <div class="card shadow">

                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div class="d-flex align-items-center gap-2 flex-wrap">

                        @if($children->count() > 1)
                            <select class="form-select" style="max-width: 220px;" wire:model.live="student_id">
                                @foreach($children as $child)
                                    <option value="{{ $child->id }}">{{ $child->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <span class="fw-bold">{{ $student->name }}</span>
                        @endif

                    </div>

                    <input type="month" class="form-control" style="max-width: 200px;"
                        wire:model.live="month">

                </div>

                <div class="card-body">

                    @if($summary)

                        <div class="row text-center mb-4">

                            <div class="col-3">
                                <div class="border rounded p-2">
                                    <h4 class="text-success mb-0">{{ $summary['present'] }}</h4>
                                    <small>Present</small>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="border rounded p-2">
                                    <h4 class="text-danger mb-0">{{ $summary['absent'] }}</h4>
                                    <small>Absent</small>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="border rounded p-2">
                                    <h4 class="text-warning mb-0">{{ $summary['leave'] }}</h4>
                                    <small>Leave</small>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="border rounded p-2">
                                    <h4 class="mb-0">
                                        @if($summary['total'])
                                            {{ round($summary['present'] / $summary['total'] * 100) }}%
                                        @else
                                            —
                                        @endif
                                    </h4>
                                    <small>Attendance</small>
                                </div>
                            </div>

                        </div>

                    @endif

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($records as $record)

                                    <tr>
                                        <td>{{ \Illuminate\Support\Carbon::parse($record->attendance_date)->format('d M Y') }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($record->attendance_date)->format('l') }}</td>
                                        <td>
                                            <span class="badge {{ $record->status === 'Present' ? 'bg-success' : ($record->status === 'Leave' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                {{ $record->status }}
                                            </span>
                                        </td>
                                        <td>{{ $record->remarks }}</td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="4" class="text-center">Is month ki attendance abhi mark nahi hui.</td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        @endif

    </div>
</div>
