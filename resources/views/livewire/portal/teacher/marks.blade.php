<div>

    <div class="container-fluid py-4">

    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h3 class="fw-bold mb-4">📝 Enter Exam Marks</h3>

    @if($exams->isEmpty())

        <div class="alert alert-warning">
            Aapko abhi tak koi batch/subject assign nahi hui hai. Administrator se sampark karein.
        </div>

    @else

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Marks Entry</h4>
        </div>

        <div class="card-body">

            <div class="row mb-4">

                <div class="col-md-5">

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

                <div class="col-md-5">

                    <label>Subject</label>

                    <select class="form-select" wire:model.live="schedule_id">

                        <option value="">-- Select Subject --</option>

                        @foreach($schedules as $scheduleOption)
                            <option value="{{ $scheduleOption->id }}">
                                {{ $scheduleOption->subject?->name }}
                                ({{ \Illuminate\Support\Carbon::parse($scheduleOption->exam_date)->format('d M Y') }},
                                Max: {{ $scheduleOption->total_marks }})
                            </option>
                        @endforeach

                    </select>

                </div>

            </div>

            @if($schedule && count($rows))

                <table class="table table-bordered">

                    <thead class="table-dark">

                        <tr>
                            <th>#</th>
                            <th>Admission No</th>
                            <th>Student</th>
                            <th width="160">Marks (out of {{ $schedule->total_marks }})</th>
                            <th width="100">Absent</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($rows as $index => $row)

                            <tr>

                                <td>{{ $index + 1 }}</td>

                                <td>{{ $row['admission_no'] }}</td>

                                <td>{{ $row['name'] }}</td>

                                <td>

                                    <input
                                        type="number"
                                        step="0.01"
                                        class="form-control form-control-sm"
                                        wire:model="rows.{{ $index }}.marks"
                                        @if($row['is_absent']) disabled @endif>

                                    @error('rows.' . $index . '.marks')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                </td>

                                <td class="text-center">

                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        wire:model.live="rows.{{ $index }}.is_absent">

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

                <button class="btn btn-primary" wire:click="save">
                    💾 Save Results
                </button>

            @elseif($schedule)

                <div class="alert alert-warning mb-0">
                    No active students found for this exam's course and batch.
                </div>

            @endif

        </div>

    </div>

    @endif

    </div>

</div>
