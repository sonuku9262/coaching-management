<div>
    <div class="container-fluid py-4">

        <div class="card shadow d-print-none mb-4">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Exam Admit Cards</h4>

                @if($exam && $students->isNotEmpty())
                    <button class="btn btn-light" onclick="window.print()">
                        🖨 Print All
                    </button>
                @endif

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Exam</label>
                        <select class="form-select" wire:model.live="exam_id">
                            <option value="">-- Select Exam --</option>
                            @foreach($exams as $examOption)
                                <option value="{{ $examOption->id }}">
                                    {{ $examOption->name }} ({{ $examOption->course?->name }} / {{ $examOption->batch?->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                @if($exam && $exam->schedules->isEmpty())
                    <div class="alert alert-warning mb-0">This exam has no subjects scheduled yet.</div>
                @elseif($exam && $students->isEmpty())
                    <div class="alert alert-info mb-0">No active students found for this exam's course and batch.</div>
                @endif

            </div>

        </div>

        @if($exam && $students->isNotEmpty() && $exam->schedules->isNotEmpty())

            @foreach($students as $student)

                <div class="admit-card">

                    <div class="text-center mb-3">

                        @if(\App\Models\Setting::get('institute_logo'))
                            <img src="{{ asset('storage/' . \App\Models\Setting::get('institute_logo')) }}" height="55">
                        @endif

                        <h3 class="mb-0">{{ \App\Models\Setting::get('institute_name', 'Coaching Institute') }}</h3>

                        @if(\App\Models\Setting::get('institute_address'))
                            <small>{{ \App\Models\Setting::get('institute_address') }}</small>
                        @endif

                        <h5 class="mt-2 text-decoration-underline">Admit Card — {{ $exam->name }}</h5>

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-8">
                            <div class="d-flex gap-3">

                                <div class="admit-card-photo">
                                    @if($student->photo)
                                        <img src="{{ asset('storage/' . $student->photo) }}">
                                    @else
                                        <span>{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                    @endif
                                </div>

                                <div>
                                    <p class="mb-1"><strong>Student:</strong> {{ $student->name }}</p>
                                    <p class="mb-1"><strong>Admission No:</strong> {{ $student->admission_no }}</p>
                                    <p class="mb-1"><strong>Father's Name:</strong> {{ $student->father_name }}</p>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-4 text-md-end">
                            <p class="mb-1"><strong>Course:</strong> {{ $exam->course?->name }}</p>
                            <p class="mb-1"><strong>Batch:</strong> {{ $exam->batch?->name }}</p>
                        </div>

                    </div>

                    <table class="table table-bordered">

                        <thead class="table-dark">
                            <tr>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Max Marks</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($exam->schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->subject?->name }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($schedule->exam_date)->format('d M Y') }}</td>
                                    <td>
                                        @if($schedule->start_time)
                                            {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $schedule->total_marks }}</td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                    <div class="mt-3">
                        <strong>Instructions:</strong>
                        <ul class="mb-0">
                            <li>Bring this admit card to every exam listed above.</li>
                            <li>Reach the exam center at least 15 minutes before the start time.</li>
                            <li>No electronic devices are allowed inside the exam hall.</li>
                        </ul>
                    </div>

                    <div class="row mt-5">
                        <div class="col-6">
                            <p class="border-top d-inline-block px-4 pt-1">Student Signature</p>
                        </div>
                        <div class="col-6 text-end">
                            <p class="border-top d-inline-block px-4 pt-1">Principal / Director</p>
                        </div>
                    </div>

                </div>

            @endforeach

        @endif

    </div>

    <style>
    .admit-card {
        border: 1px solid #333;
        border-radius: 6px;
        padding: 24px;
        margin-bottom: 24px;
        background: #fff;
        page-break-after: always;
        break-after: page;
    }

    .admit-card-photo {
        width: 70px;
        height: 85px;
        border: 1px solid #999;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eee;
        font-size: 30px;
        font-weight: bold;
        color: #888;
        overflow: hidden;
    }

    .admit-card-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    @media print {
        .admit-card:last-child {
            page-break-after: auto;
            break-after: auto;
        }
    }
    </style>
</div>
