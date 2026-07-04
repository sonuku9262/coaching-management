@extends('layouts.frontend')

@section('title', 'Contact & Admission Enquiry — ' . \App\Models\Setting::get('institute_name', 'Coaching Institute'))

@section('content')

    <!-- Page Header -->
    <section class="hero-section text-white py-5">
        <div class="container text-center">
            <h1 class="fw-bold">Contact & Admission Enquiry</h1>
            <p class="lead text-white-50 mb-0">Hum aapki madad ke liye taiyaar hain</p>
        </div>
    </section>

    <section class="py-5">

        <div class="container">

            <div class="row g-5">

                <!-- Enquiry Form -->
                <div class="col-lg-7">

                    <div class="card border-0 shadow rounded-4">

                        <div class="card-body p-4">

                            <h4 class="fw-bold mb-1">📝 Admission Enquiry Form</h4>
                            <p class="text-muted mb-4">Form bharein — hamari team aapko jald contact karegi.</p>

                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('enquiry.submit') }}">

                                @csrf

                                <div class="row g-3">

                                    <div class="col-md-6">

                                        <label class="form-label">Full Name *</label>

                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control @error('name') is-invalid @enderror">

                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    <div class="col-md-6">

                                        <label class="form-label">Mobile Number *</label>

                                        <input type="text" name="mobile" value="{{ old('mobile') }}"
                                            class="form-control @error('mobile') is-invalid @enderror">

                                        @error('mobile')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    <div class="col-md-6">

                                        <label class="form-label">Email</label>

                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror">

                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    <div class="col-md-6">

                                        <label class="form-label">Interested Course</label>

                                        <select name="course_id" class="form-select @error('course_id') is-invalid @enderror">

                                            <option value="">-- Select Course --</option>

                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>
                                                    {{ $course->name }}
                                                </option>
                                            @endforeach

                                        </select>

                                        @error('course_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    <div class="col-12">

                                        <label class="form-label">Message</label>

                                        <textarea name="message" rows="4"
                                            class="form-control @error('message') is-invalid @enderror"
                                            placeholder="Apna sawaal ya requirement likhein...">{{ old('message') }}</textarea>

                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    <div class="col-12">

                                        <button type="submit" class="btn btn-primary btn-lg">
                                            Submit Enquiry →
                                        </button>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

                <!-- Contact Info -->
                <div class="col-lg-5">

                    <div class="card border-0 shadow rounded-4 mb-4">

                        <div class="card-body p-4">

                            <h5 class="fw-bold mb-3">📍 Contact Information</h5>

                            <p class="mb-3">
                                <strong>Address</strong><br>
                                {{ \App\Models\Setting::get('institute_address', 'Address will be updated soon.') }}
                            </p>

                            @if(\App\Models\Setting::get('institute_phone'))
                                <p class="mb-3">
                                    <strong>Phone</strong><br>
                                    <a href="tel:{{ \App\Models\Setting::get('institute_phone') }}" class="text-decoration-none">
                                        {{ \App\Models\Setting::get('institute_phone') }}
                                    </a>
                                </p>
                            @endif

                            @if(\App\Models\Setting::get('institute_email'))
                                <p class="mb-3">
                                    <strong>Email</strong><br>
                                    <a href="mailto:{{ \App\Models\Setting::get('institute_email') }}" class="text-decoration-none">
                                        {{ \App\Models\Setting::get('institute_email') }}
                                    </a>
                                </p>
                            @endif

                            @if(\App\Models\Setting::get('institute_website'))
                                <p class="mb-0">
                                    <strong>Website</strong><br>
                                    {{ \App\Models\Setting::get('institute_website') }}
                                </p>
                            @endif

                        </div>

                    </div>

                    <div class="card border-0 shadow rounded-4 bg-primary text-white">

                        <div class="card-body p-4 text-center">

                            <h5 class="fw-bold">🔐 Already Enrolled?</h5>

                            <p class="text-white-50">
                                Student, Parent ya Teacher — apne portal me login karein.
                            </p>

                            <a href="/login" class="btn btn-warning fw-bold">
                                Login to Portal
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection
