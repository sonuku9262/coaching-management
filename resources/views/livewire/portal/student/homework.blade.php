<div>
    <div class="container-fluid py-4">

        <h3 class="fw-bold mb-4">📔 Homework</h3>

        @if(! $student)

            <div class="alert alert-warning">
                Your login is not linked to a student registration yet. Please contact the administrator.
            </div>

        @elseif($homeworks->isEmpty())

            <div class="alert alert-info">
                Abhi tak koi homework assign nahi hua hai.
            </div>

        @else

            <div class="card shadow">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead class="table-dark">
                                <tr>
                                    <th>Title</th>
                                    <th>Subject</th>
                                    <th>Description</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Attachment</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($homeworks as $homework)
                                    @php
                                        $dueDate = \Illuminate\Support\Carbon::parse($homework->due_date);
                                        $isOverdue = $dueDate->isPast() && ! $dueDate->isToday();
                                    @endphp
                                    <tr>
                                        <td>{{ $homework->title }}</td>
                                        <td>{{ $homework->subject?->name }}</td>
                                        <td>{{ $homework->description }}</td>
                                        <td>{{ $dueDate->format('d M Y') }}</td>
                                        <td>
                                            @if($isOverdue)
                                                <span class="badge bg-danger">Overdue</span>
                                            @elseif($dueDate->isToday())
                                                <span class="badge bg-warning text-dark">Due Today</span>
                                            @else
                                                <span class="badge bg-success">Upcoming</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($homework->file_path)
                                                <a href="{{ asset('storage/' . $homework->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                    ⬇ Download
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

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
</div>
