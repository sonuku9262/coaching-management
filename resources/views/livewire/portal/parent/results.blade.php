<div>
    <div class="container-fluid py-4">

        <h3 class="fw-bold mb-4">📝 Child Results</h3>

        @if($children->isEmpty())

            <div class="alert alert-warning">
                No student is linked to your account yet. Please contact the administrator.
            </div>

        @else

            @if($children->count() > 1)
                <div class="mb-3" style="max-width: 260px;">
                    <select class="form-select" wire:model.live="student_id">
                        @foreach($children as $child)
                            <option value="{{ $child->id }}">{{ $child->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($exams->isEmpty())

                <div class="alert alert-info">
                    Abhi koi result publish nahi hua hai.
                </div>

            @else

                @foreach($exams as $examData)

                    <div class="card shadow mb-4">

                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                            <span class="fw-bold">{{ $examData['exam']->name }}</span>

                            @if($examData['percent'] !== null)
                                <span class="badge {{ $examData['result'] === 'PASS' ? 'bg-success' : 'bg-danger' }} fs-6">
                                    {{ $examData['percent'] }}% — {{ $examData['result'] }}
                                </span>
                            @endif

                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered mb-0">

                                    <thead class="table-dark">
                                        <tr>
                                            <th>Subject</th>
                                            <th>Total Marks</th>
                                            <th>Passing Marks</th>
                                            <th>Marks Obtained</th>
                                            <th>Result</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach($examData['rows'] as $row)

                                            <tr>
                                                <td>{{ $row['subject'] }}</td>
                                                <td>{{ $row['total'] }}</td>
                                                <td>{{ $row['passing'] }}</td>
                                                <td>
                                                    @if($row['absent'])
                                                        <span class="badge bg-secondary">Absent</span>
                                                    @else
                                                        {{ $row['marks'] }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($row['absent'])
                                                        —
                                                    @elseif($row['pass'])
                                                        <span class="badge bg-success">Pass</span>
                                                    @else
                                                        <span class="badge bg-danger">Fail</span>
                                                    @endif
                                                </td>
                                            </tr>

                                        @endforeach

                                    </tbody>

                                    <tfoot class="fw-bold">
                                        <tr>
                                            <td>Total</td>
                                            <td>{{ $examData['total'] }}</td>
                                            <td></td>
                                            <td colspan="2">{{ $examData['obtained'] }} ({{ $examData['percent'] }}%)</td>
                                        </tr>
                                    </tfoot>

                                </table>

                            </div>

                        </div>

                    </div>

                @endforeach

            @endif

        @endif

    </div>
</div>
