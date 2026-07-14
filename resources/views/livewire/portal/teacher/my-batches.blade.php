<div>
    <div class="container-fluid py-4">

        <h3 class="fw-bold mb-4">🗂️ My Batches</h3>

        @if(! $teacher)

            <div class="alert alert-warning">
                Your login is not linked to a teacher profile yet. Please contact the administrator.
            </div>

        @elseif($assignments->isEmpty())

            <div class="alert alert-info">
                Aapko abhi tak koi batch/subject assign nahi hui hai. Administrator se sampark karein.
            </div>

        @else

            <div class="row g-3">

                @foreach($assignments as $row)

                    @php $assignment = $row['assignment']; @endphp

                    <div class="col-md-4">

                        <div class="card shadow h-100">

                            <div class="card-header bg-primary text-white">
                                {{ $assignment->batch?->name }}
                            </div>

                            <div class="card-body">

                                <p class="mb-1"><strong>Course:</strong> {{ $assignment->batch?->course?->name }}</p>
                                <p class="mb-1"><strong>Subject:</strong> {{ $assignment->subject?->name }}</p>
                                <p class="mb-3"><strong>Students:</strong> {{ $row['studentCount'] }}</p>

                                <div class="d-flex gap-2 flex-wrap">

                                    <a href="/teacher/attendance?batch_id={{ $assignment->batch_id }}" class="btn btn-outline-primary btn-sm">
                                        ✅ Attendance
                                    </a>

                                    <a href="/teacher/marks?batch_id={{ $assignment->batch_id }}&subject_id={{ $assignment->subject_id }}" class="btn btn-outline-success btn-sm">
                                        📝 Enter Marks
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>
</div>
