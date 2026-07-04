<div>

    <div class="container-fluid py-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">Report Card</h4>

            @if($summary)
            <button class="btn btn-light" onclick="window.print()">
                🖨 Print
            </button>
            @endif

        </div>

        <div class="card-body">

            <div class="row mb-4 d-print-none">

                <div class="col-md-6">
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

                <div class="col-md-6">
                    <label>Student</label>
                    <select class="form-select" wire:model.live="student_id">
                        <option value="">-- Select Student --</option>
                        @foreach($students as $studentOption)
                            <option value="{{ $studentOption->id }}">
                                {{ $studentOption->name }} ({{ $studentOption->admission_no }})
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            @if($exam && $student && $summary)

                <div class="border rounded p-4">

                    <div class="text-center mb-3">

                        @if(\App\Models\Setting::get('institute_logo'))
                            <img src="{{ asset('storage/' . \App\Models\Setting::get('institute_logo')) }}" height="60">
                        @endif

                        <h3 class="mb-0">{{ \App\Models\Setting::get('institute_name', 'Coaching Institute') }}</h3>

                        @if(\App\Models\Setting::get('institute_address'))
                            <small>{{ \App\Models\Setting::get('institute_address') }}</small>
                        @endif

                        <h5 class="mt-2 text-decoration-underline">{{ $exam->name }} — Report Card</h5>

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <p class="mb-1"><strong>Student:</strong> {{ $student->name }}</p>
                            <p class="mb-1"><strong>Admission No:</strong> {{ $student->admission_no }}</p>
                            <p class="mb-1"><strong>Father's Name:</strong> {{ $student->father_name }}</p>
                        </div>

                        <div class="col-md-6 text-md-end">
                            <p class="mb-1"><strong>Course:</strong> {{ $exam->course?->name }}</p>
                            <p class="mb-1"><strong>Batch:</strong> {{ $exam->batch?->name }}</p>
                        </div>

                    </div>

                    <table class="table table-bordered">

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

                            @foreach($rows as $row)
                                <tr>
                                    <td>{{ $row['subject'] }}</td>
                                    <td>{{ $row['total'] }}</td>
                                    <td>{{ $row['passing'] }}</td>
                                    <td>
                                        @if($row['absent'])
                                            <span class="badge bg-secondary">Absent</span>
                                        @elseif(! $row['entered'])
                                            <span class="text-muted">Not Entered</span>
                                        @else
                                            {{ $row['marks'] }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(! $row['entered'])
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
                                <td>{{ $summary['total'] }}</td>
                                <td></td>
                                <td>{{ $summary['obtained'] }}</td>
                                <td>
                                    @if($summary['percent'] !== null)
                                        {{ $summary['percent'] }}% —
                                        <span class="{{ $summary['result'] === 'PASS' ? 'text-success' : 'text-danger' }}">
                                            {{ $summary['result'] }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>

                    </table>

                    <div class="row mt-5">
                        <div class="col-6">
                            <p class="border-top d-inline-block px-4 pt-1">Class Teacher</p>
                        </div>
                        <div class="col-6 text-end">
                            <p class="border-top d-inline-block px-4 pt-1">Principal / Director</p>
                        </div>
                    </div>

                </div>

            @elseif($exam)

                <div class="alert alert-info mb-0">
                    Select a student to view the report card.
                </div>

            @endif

        </div>

    </div>

</div>

</div>
