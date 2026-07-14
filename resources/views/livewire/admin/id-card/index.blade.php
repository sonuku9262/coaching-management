<div>
    <div class="container-fluid py-4">

        <div class="card shadow d-print-none mb-4">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Student ID Cards</h4>

                @if($students->isNotEmpty())
                    <button class="btn btn-light" onclick="window.print()">
                        🖨 Print All
                    </button>
                @endif

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Course</label>
                        <select class="form-select" wire:model.live="course_id">
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Batch</label>
                        <select class="form-select" wire:model.live="batch_id">
                            <option value="">Select Batch</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                @if($batch_id && $students->isEmpty())
                    <div class="alert alert-info mb-0">No active students found in this batch.</div>
                @endif

            </div>

        </div>

        @if($students->isNotEmpty())

            <div class="id-card-grid">

                @foreach($students as $student)

                    <div class="id-card">

                        <div class="id-card-header">

                            @if(\App\Models\Setting::get('institute_logo'))
                                <img src="{{ asset('storage/' . \App\Models\Setting::get('institute_logo')) }}" class="id-card-logo">
                            @endif

                            <div class="id-card-institute-name">
                                {{ \App\Models\Setting::get('institute_name', 'Coaching Institute') }}
                            </div>

                        </div>

                        <div class="id-card-body">

                            <div class="id-card-photo">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}">
                                @else
                                    <span>{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                @endif
                            </div>

                            <div class="id-card-details">
                                <div class="id-card-name">{{ $student->name }}</div>
                                <div><strong>Adm No:</strong> {{ $student->admission_no }}</div>
                                <div><strong>Course:</strong> {{ $student->course?->name }}</div>
                                <div><strong>Batch:</strong> {{ $student->batch?->name }}</div>
                                <div><strong>Father:</strong> {{ $student->father_name }}</div>
                                <div><strong>Mobile:</strong> {{ $student->mobile }}</div>
                            </div>

                        </div>

                        <div class="id-card-footer">
                            <span>Valid: {{ now()->format('Y') }} - {{ now()->addYear()->format('Y') }}</span>
                            <span class="id-card-signature">Authorized Signatory</span>
                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>

    <style>
    .id-card-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
    }

    .id-card {
        width: 3.375in;
        height: 2.125in;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 8px 10px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: #fff;
        font-size: 10px;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .id-card-header {
        display: flex;
        align-items: center;
        gap: 6px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 4px;
    }

    .id-card-logo {
        height: 24px;
        width: 24px;
        object-fit: contain;
    }

    .id-card-institute-name {
        font-weight: bold;
        font-size: 11px;
        line-height: 1.1;
    }

    .id-card-body {
        display: flex;
        gap: 8px;
        flex: 1;
        align-items: flex-start;
        padding-top: 4px;
    }

    .id-card-photo {
        width: 60px;
        height: 72px;
        border: 1px solid #999;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eee;
        font-size: 28px;
        font-weight: bold;
        color: #888;
        overflow: hidden;
    }

    .id-card-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .id-card-details {
        line-height: 1.4;
    }

    .id-card-name {
        font-weight: bold;
        font-size: 12px;
        margin-bottom: 2px;
    }

    .id-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        border-top: 1px solid #ccc;
        padding-top: 4px;
        font-size: 9px;
        color: #444;
    }

    .id-card-signature {
        border-top: 1px solid #444;
        padding-top: 2px;
    }

    @media print {
        .id-card-grid {
            gap: 0.15in;
        }
    }
    </style>
</div>
