@extends('layouts.frontend')

@section('content')

    <!-- Hero -->
    <section class="hero-section text-white py-5">

        <!-- floating decorative shapes -->
        <span class="floating-shape" style="width: 140px; height: 140px; top: 8%; left: 4%;"></span>
        <span class="floating-shape float-slow" style="width: 90px; height: 90px; bottom: 12%; left: 42%;"></span>
        <span class="floating-shape float-fast" style="width: 60px; height: 60px; top: 18%; right: 8%;"></span>
        <span class="floating-shape float-slow" style="width: 200px; height: 200px; bottom: -60px; right: -40px;"></span>

        <div class="container py-4 position-relative">

            <div class="row align-items-center">

                <div class="col-lg-7">

                    <span class="badge bg-warning text-dark fs-6 mb-3 hero-enter hero-enter-1">🎓 Admissions Open {{ date('Y') }}</span>

                    <h1 class="display-4 fw-bold hero-enter hero-enter-2">
                        {{ \App\Models\Setting::get('hero_title', \App\Models\Setting::get('institute_name', 'Welcome to Our Coaching Institute')) }}
                    </h1>

                    <p class="lead mt-3 text-white-50 hero-enter hero-enter-3">
                        {{ \App\Models\Setting::get('hero_subtitle', 'Expert faculty, smart classrooms, regular tests aur personal attention — aapki safalta hamari zimmedari. Students, Parents aur Teachers sabke liye apna online portal.') }}
                    </p>

                    <div class="d-flex flex-wrap gap-3 mt-4 hero-enter hero-enter-4">

                        <a href="/contact" class="btn btn-warning btn-lg fw-bold btn-pulse">
                            📝 Admission Enquiry
                        </a>

                        <a href="/courses" class="btn btn-outline-light btn-lg">
                            📚 View Courses
                        </a>

                    </div>

                    <img src="{{ asset('images/hero-illustration.svg') }}"
                        alt="Education illustration"
                        class="hero-illustration d-none d-lg-block mt-4"
                        style="max-height: 240px;">

                </div>

                <div class="col-lg-5 mt-5 mt-lg-0">

                    <!-- Portal Login Card -->
                    <div class="card shadow-lg border-0 rounded-4 hero-enter hero-enter-3">

                        <div class="card-body p-4 text-center">

                            <h4 class="fw-bold mb-1 text-dark">🔐 Portal Login</h4>

                            <p class="text-muted mb-4">Sabhi ke liye ek hi login</p>

                            <div class="row g-3">

                                <div class="col-6">
                                    <a href="/login" class="btn btn-outline-primary w-100 py-3 hover-lift">
                                        🎓<br>Student
                                    </a>
                                </div>

                                <div class="col-6">
                                    <a href="/login" class="btn btn-outline-success w-100 py-3 hover-lift">
                                        👨‍👩‍👦<br>Parent
                                    </a>
                                </div>

                                <div class="col-6">
                                    <a href="/login" class="btn btn-outline-info w-100 py-3 hover-lift">
                                        👨‍🏫<br>Teacher
                                    </a>
                                </div>

                                <div class="col-6">
                                    <a href="/login" class="btn btn-outline-dark w-100 py-3 hover-lift">
                                        🛡️<br>Admin / Staff
                                    </a>
                                </div>

                            </div>

                            <small class="text-muted d-block mt-3">
                                Login karne ke baad apne role ka dashboard automatically khulega.
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- Notices -->
    @if($notices->isNotEmpty())
    <section class="bg-warning py-2">

        <div class="container">

            <div class="d-flex align-items-center gap-3 overflow-hidden">

                <span class="badge bg-dark flex-shrink-0">📢 Notice</span>

                <marquee behavior="scroll" direction="left" scrollamount="5" class="fw-semibold text-dark">

                    @foreach($notices as $notice)
                        {{ \Illuminate\Support\Carbon::parse($notice->notice_date)->format('d M') }} — {{ $notice->title }}
                        @if(! $loop->last) &nbsp;&nbsp;•&nbsp;&nbsp; @endif
                    @endforeach

                </marquee>

            </div>

        </div>

    </section>
    @endif

    <!-- Stats -->
    <section class="py-4 bg-white border-bottom">

        <div class="container">

            <div class="row text-center">

                <div class="col-6 col-md-3 py-3 reveal">
                    <h2 class="fw-bold text-primary mb-0" data-count="{{ $stats['students'] }}" data-suffix="+">0+</h2>
                    <p class="text-muted mb-0">Students</p>
                </div>

                <div class="col-6 col-md-3 py-3 reveal delay-1">
                    <h2 class="fw-bold text-primary mb-0" data-count="{{ $stats['teachers'] }}" data-suffix="+">0+</h2>
                    <p class="text-muted mb-0">Expert Teachers</p>
                </div>

                <div class="col-6 col-md-3 py-3 reveal delay-2">
                    <h2 class="fw-bold text-primary mb-0" data-count="{{ $stats['courses'] }}" data-suffix="+">0+</h2>
                    <p class="text-muted mb-0">Courses</p>
                </div>

                <div class="col-6 col-md-3 py-3 reveal delay-3">
                    <h2 class="fw-bold text-primary mb-0" data-count="{{ $stats['batches'] }}" data-suffix="+">0+</h2>
                    <p class="text-muted mb-0">Running Batches</p>
                </div>

            </div>

        </div>

    </section>

    <!-- About Split -->
    <section class="py-5">

        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-lg-6 reveal-left">

                    <img src="{{ asset('images/about-illustration.svg') }}"
                        alt="Classroom illustration" class="img-fluid">

                </div>

                <div class="col-lg-6 reveal-right">

                    <h2 class="fw-bold mb-3">
                        {{ \App\Models\Setting::get('institute_name', 'Hamara Institute') }} Kyun?
                    </h2>

                    <p class="text-muted">
                        {{ \App\Models\Setting::get('about_text', 'Experienced faculty, chhote batch size aur personal attention ke saath hum har student ki progress par focus karte hain. Modern management system ke saath parents ko attendance, fees aur results ki puri jaankari online milti hai.') }}
                    </p>

                    <div class="row g-3 mt-2">

                        <div class="col-6 d-flex align-items-center gap-2">
                            <span class="fs-4">✅</span> <span class="fw-semibold">Expert Faculty</span>
                        </div>

                        <div class="col-6 d-flex align-items-center gap-2">
                            <span class="fs-4">✅</span> <span class="fw-semibold">Daily Attendance Alerts</span>
                        </div>

                        <div class="col-6 d-flex align-items-center gap-2">
                            <span class="fs-4">✅</span> <span class="fw-semibold">Regular Tests</span>
                        </div>

                        <div class="col-6 d-flex align-items-center gap-2">
                            <span class="fs-4">✅</span> <span class="fw-semibold">Online Portal</span>
                        </div>

                    </div>

                    <a href="/about" class="btn btn-primary mt-4">
                        Know More →
                    </a>

                </div>

            </div>

        </div>

    </section>

    <!-- Why Choose Us -->
    <section class="py-5 bg-light">

        <div class="container">

            <div class="text-center mb-5 reveal">
                <h2 class="fw-bold section-title">Why Choose Us</h2>
                <p class="text-muted mt-3">Hamari khasiyat jo humein sabse alag banati hai</p>
            </div>

            <div class="row g-4">

                <div class="col-md-3 col-6 reveal">
                    <div class="card border-0 shadow h-100 hover-lift">
                        <div class="card-body text-center py-4">
                            <div class="icon-circle bg-primary bg-opacity-10 mb-3">👨‍🏫</div>
                            <h5 class="fw-bold">Expert Faculty</h5>
                            <p class="text-muted mb-0 small">Experienced aur qualified teachers</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6 reveal delay-1">
                    <div class="card border-0 shadow h-100 hover-lift">
                        <div class="card-body text-center py-4">
                            <div class="icon-circle bg-success bg-opacity-10 mb-3">📋</div>
                            <h5 class="fw-bold">Daily Attendance</h5>
                            <p class="text-muted mb-0 small">Parents ko absent hone par turant alert</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6 reveal delay-2">
                    <div class="card border-0 shadow h-100 hover-lift">
                        <div class="card-body text-center py-4">
                            <div class="icon-circle bg-warning bg-opacity-10 mb-3">📝</div>
                            <h5 class="fw-bold">Regular Tests</h5>
                            <p class="text-muted mb-0 small">Report card ke saath progress tracking</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6 reveal delay-3">
                    <div class="card border-0 shadow h-100 hover-lift">
                        <div class="card-body text-center py-4">
                            <div class="icon-circle bg-info bg-opacity-10 mb-3">💻</div>
                            <h5 class="fw-bold">Online Portal</h5>
                            <p class="text-muted mb-0 small">Attendance, fees aur results — sab online</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>

    <!-- Popular Courses -->
    <section class="py-5">

        <div class="container">

            <div class="text-center mb-5 reveal">
                <h2 class="fw-bold section-title">Our Courses</h2>
                <p class="text-muted mt-3">Apne career ke liye best course chunein</p>
            </div>

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

                                <h5 class="fw-bold">{{ $course->name }}</h5>

                                <p class="text-muted small">
                                    {{ \Illuminate\Support\Str::limit($course->description, 90) ?: 'Career-focused course with expert guidance.' }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center">

                                    <span class="badge bg-primary">
                                        ⏱ {{ $course->duration }} {{ $course->duration_type }}
                                    </span>

                                    <span class="fw-bold text-success">
                                        ₹ {{ number_format($course->fees) }}
                                    </span>

                                </div>

                            </div>

                            <div class="card-footer bg-white border-0 pb-3">
                                <a href="/contact" class="btn btn-outline-primary w-100">
                                    Enquire Now
                                </a>
                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-12 text-center text-muted">
                        Courses jald hi update honge.
                    </div>

                @endforelse

            </div>

            <div class="text-center mt-4 reveal">
                <a href="/courses" class="btn btn-primary btn-lg">
                    View All Courses →
                </a>
            </div>

        </div>

    </section>

    <!-- Teachers -->
    @if($teachers->isNotEmpty())
    <section class="py-5 bg-light">

        <div class="container">

            <div class="text-center mb-5 reveal">
                <h2 class="fw-bold section-title">Meet Our Faculty</h2>
                <p class="text-muted mt-3">Experienced aur dedicated teachers se seekhein</p>
            </div>

            <div class="row g-4 justify-content-center">

                @foreach($teachers as $teacher)

                    <div class="col-lg-3 col-md-6 reveal-zoom {{ 'delay-' . ($loop->index % 4) }}">

                        <div class="card border-0 shadow h-100 hover-lift text-center">

                            <div class="img-zoom-wrap">
                                @if($teacher->photo)
                                    <img src="{{ asset('storage/' . $teacher->photo) }}"
                                        class="card-img-top" style="height: 260px; object-fit: cover;">
                                @else
                                    <div class="bg-primary bg-opacity-10 d-flex align-items-center justify-content-center course-thumb"
                                        style="height: 260px; font-size: 5rem;">
                                        👨‍🏫
                                    </div>
                                @endif
                            </div>

                            <div class="card-body">
                                <h5 class="fw-bold mb-1">{{ $teacher->name }}</h5>
                                <p class="text-muted mb-0">{{ $teacher->qualification }}</p>
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </section>
    @endif

    <!-- Testimonials -->
    @if($testimonials->isNotEmpty())
    <section class="py-5">

        <div class="container">

            <div class="text-center mb-5 reveal">
                <h2 class="fw-bold section-title">What Our Students Say</h2>
                <p class="text-muted mt-3">Hamare students aur parents ka bharosa</p>
            </div>

            <div class="row g-4">

                @foreach($testimonials as $testimonial)

                    <div class="col-lg-4 col-md-6 reveal {{ 'delay-' . ($loop->index % 3) }}">

                        <div class="card border-0 shadow h-100 hover-lift">

                            <div class="card-body p-4">

                                <p class="text-warning mb-2 fs-5">
                                    {{ str_repeat('★', $testimonial->rating) }}{{ str_repeat('☆', 5 - $testimonial->rating) }}
                                </p>

                                <p class="text-muted fst-italic">
                                    "{{ \Illuminate\Support\Str::limit($testimonial->message, 180) }}"
                                </p>

                                <div class="d-flex align-items-center gap-3 mt-3">

                                    @if($testimonial->photo)
                                        <img src="{{ asset('storage/' . $testimonial->photo) }}"
                                            width="48" height="48" class="rounded-circle" style="object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                            style="width: 48px; height: 48px;">
                                            👤
                                        </div>
                                    @endif

                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $testimonial->name }}</h6>
                                        <small class="text-muted">{{ $testimonial->designation }}</small>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </section>
    @endif

    <!-- CTA -->
    <section class="hero-section text-white py-5">

        <span class="floating-shape" style="width: 120px; height: 120px; top: 10%; left: 6%;"></span>
        <span class="floating-shape float-fast" style="width: 70px; height: 70px; bottom: 16%; right: 10%;"></span>

        <div class="container text-center py-3 position-relative">

            <h2 class="fw-bold reveal">Apna Admission Aaj Hi Book Karein!</h2>

            <p class="lead text-white-50 mb-4 reveal delay-1">
                Seats limited hain — abhi enquiry karein aur free counselling paayein.
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap reveal delay-2">

                <a href="/contact" class="btn btn-warning btn-lg fw-bold btn-pulse">
                    📝 Enquiry Now
                </a>

                @if(\App\Models\Setting::get('institute_phone'))
                    <a href="tel:{{ \App\Models\Setting::get('institute_phone') }}" class="btn btn-outline-light btn-lg">
                        📞 {{ \App\Models\Setting::get('institute_phone') }}
                    </a>
                @endif

            </div>

        </div>

    </section>

@endsection
