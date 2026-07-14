<div>
    <div class="container-fluid py-4">

        <h3 class="fw-bold mb-4">🗓️ My Timetable</h3>

        @if(! $teacher)

            <div class="alert alert-warning">
                Your login is not linked to a teacher profile yet. Please contact the administrator.
            </div>

        @elseif($slots->isEmpty())

            <div class="alert alert-info">
                Abhi tak koi class schedule nahi hui hai.
            </div>

        @else

            <div class="card shadow">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead class="table-dark">
                                <tr>
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>Batch</th>
                                    <th>Subject</th>
                                    <th>Classroom</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($slots as $slot)
                                    <tr>
                                        <td>{{ $slot->day_of_week }}</td>
                                        <td>{{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}</td>
                                        <td>{{ $slot->batch?->name }}</td>
                                        <td>{{ $slot->subject?->name }}</td>
                                        <td>{{ $slot->classroom?->name ?? '-' }}</td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        @endif

    </div>
</div>
