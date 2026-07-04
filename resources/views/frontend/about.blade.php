@extends('layouts.frontend')

@section('title', 'About Us — ' . \App\Models\Setting::get('institute_name', 'Coaching Institute'))

@section('content')

    <!-- Page Header -->
    <section class="hero-section text-white py-5">
        <div class="container text-center">
            <h1 class="fw-bold">About Us</h1>
            <p class="lead text-white-50 mb-0">Humein jaaniye — hamara mission aur hamari team</p>
        </div>
    </section>

    <!-- About -->
    <section class="py-5">

        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-lg-6">

                    <h2 class="fw-bold mb-3">
                        {{ \App\Models\Setting::get('institute_name', 'Our Coaching Institute') }}
                    </h2>

                    @if(\App\Models\Setting::get('about_text'))

                        <p class="text-muted" style="white-space: pre-line;">{{ \App\Models\Setting::get('about_text') }}</p>

                    @else

                        <p class="text-muted">
                            Hamara mission har student ko quality education dena hai. Experienced
                            faculty, chhote batch size aur personal attention ke saath hum har
                            student ki progress par focus karte hain.
                        </p>

                        <p class="text-muted">
                            Modern management system ke saath — parents ko bachche ki attendance,
                            fees aur results ki puri jaankari online milti hai.
                        </p>

                    @endif

                    <div class="row g-3 mt-3">

                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-4">✅</span>
                                <span class="fw-semibold">Expert Faculty</span>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-4">✅</span>
                                <span class="fw-semibold">Smart Classrooms</span>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-4">✅</span>
                                <span class="fw-semibold">Daily Attendance Alerts</span>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-4">✅</span>
                                <span class="fw-semibold">Regular Tests & Report Cards</span>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-4">✅</span>
                                <span class="fw-semibold">Student & Parent Portal</span>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-4">✅</span>
                                <span class="fw-semibold">Affordable Fees</span>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="card border-0 shadow rounded-4">

                        <div class="card-body p-4">

                            <h5 class="fw-bold mb-3">📍 Visit Us</h5>

                            <p class="mb-2">
                                <strong>Address:</strong><br>
                                {{ \App\Models\Setting::get('institute_address', 'Address will be updated soon.') }}
                            </p>

                            @if(\App\Models\Setting::get('institute_phone'))
                                <p class="mb-2">
                                    <strong>Phone:</strong> {{ \App\Models\Setting::get('institute_phone') }}
                                </p>
                            @endif

                            @if(\App\Models\Setting::get('institute_email'))
                                <p class="mb-2">
                                    <strong>Email:</strong> {{ \App\Models\Setting::get('institute_email') }}
                                </p>
                            @endif

                            <a href="/contact" class="btn btn-primary mt-2">
                                📝 Admission Enquiry
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- Faculty -->
    @if($teachers->isNotEmpty())
    <section class="py-5 bg-light">

        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold section-title">Our Faculty</h2>
            </div>

            <div class="row g-4 justify-content-center">

                @foreach($teachers as $teacher)

                    <div class="col-lg-3 col-md-4 col-6">

                        <div class="card border-0 shadow h-100 hover-lift text-center">

                            @if($teacher->photo)
                                <img src="{{ asset('storage/' . $teacher->photo) }}"
                                    class="card-img-top" style="height: 220px; object-fit: cover;">
                            @else
                                <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center"
                                    style="height: 220px; font-size: 4rem;">
                                    👨‍🏫
                                </div>
                            @endif

                            <div class="card-body">
                                <h6 class="fw-bold mb-1">{{ $teacher->name }}</h6>
                                <p class="text-muted small mb-0">{{ $teacher->qualification }}</p>
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </section>
    @endif

@endsection
