@extends('layouts.frontend')

@section('title', 'Courses — ' . \App\Models\Setting::get('institute_name', 'Coaching Institute'))

@section('content')

    <!-- Page Header -->
    <section class="hero-section text-white py-5">
        <span class="floating-shape" style="width: 110px; height: 110px; top: 12%; left: 5%;"></span>
        <span class="floating-shape float-fast" style="width: 70px; height: 70px; bottom: 14%; right: 8%;"></span>
        <div class="container text-center position-relative">
            <h1 class="fw-bold hero-enter hero-enter-1">Our Courses</h1>
            <p class="lead text-white-50 mb-0 hero-enter hero-enter-2">Apne career ke liye sahi course chunein</p>
        </div>
    </section>

    <section class="py-5">

        <div class="container">

            <div class="row g-4">

                @forelse($courses as $course)

                    <div class="col-lg-4 col-md-6 reveal {{ 'delay-' . ($loop->index % 3) }}">

                        <div class="card border-0 shadow h-100 hover-lift">

                            <div class="img-zoom-wrap">
                                @if($course->image)
                                    <img src="{{ asset('storage/' . $course->image) }}"
                                        class="card-img-top" style="height: 200px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/course-placeholder.svg') }}"
                                        class="card-img-top course-thumb" style="height: 200px; object-fit: cover;">
                                @endif
                            </div>

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="fw-bold">{{ $course->name }}</h5>
                                    @if($course->code)
                                        <span class="badge bg-secondary">{{ $course->code }}</span>
                                    @endif
                                </div>

                                <p class="text-muted small">
                                    {{ \Illuminate\Support\Str::limit($course->description, 120) ?: 'Career-focused course with expert guidance and regular assessments.' }}
                                </p>

                                <ul class="list-unstyled small mb-3">
                                    <li class="mb-1">⏱ <strong>Duration:</strong> {{ $course->duration }} {{ $course->duration_type }}</li>
                                    <li class="mb-1">💰 <strong>Fees:</strong> ₹ {{ number_format($course->fees) }}</li>
                                </ul>

                            </div>

                            <div class="card-footer bg-white border-0 pb-3">
                                <a href="/contact" class="btn btn-primary w-100">
                                    📝 Enquire for Admission
                                </a>
                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-12 text-center text-muted py-5">
                        <h4>Courses jald hi update honge.</h4>
                    </div>

                @endforelse

            </div>

        </div>

    </section>

@endsection
