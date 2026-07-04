<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow sticky-top">

    <div class="container">

        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/">

            @if(\App\Models\Setting::get('institute_logo'))
                <img src="{{ asset('storage/' . \App\Models\Setting::get('institute_logo')) }}"
                    height="34" class="bg-white rounded p-1">
            @endif

            {{ \App\Models\Setting::get('institute_name', 'Coaching ERP') }}

        </a>

        <button class="navbar-toggler"
            data-bs-toggle="collapse"
            data-bs-target="#navbar">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbar">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a href="/" class="nav-link {{ request()->is('/') ? 'active fw-bold' : '' }}">Home</a>
                </li>

                <li class="nav-item">
                    <a href="/about" class="nav-link {{ request()->is('about') ? 'active fw-bold' : '' }}">About</a>
                </li>

                <li class="nav-item">
                    <a href="/courses" class="nav-link {{ request()->is('courses') ? 'active fw-bold' : '' }}">Courses</a>
                </li>

                <li class="nav-item">
                    <a href="/gallery" class="nav-link {{ request()->is('gallery') ? 'active fw-bold' : '' }}">Gallery</a>
                </li>

                <li class="nav-item">
                    <a href="/contact" class="nav-link {{ request()->is('contact') ? 'active fw-bold' : '' }}">Contact</a>
                </li>

                @auth

                    <li class="nav-item ms-lg-2">
                        <a href="/dashboard" class="btn btn-light">
                            📊 My Dashboard
                        </a>
                    </li>

                @else

                    <li class="nav-item ms-lg-2">
                        <a href="/login" class="btn btn-warning fw-bold">
                            🔐 Login
                        </a>
                    </li>

                @endauth

            </ul>

        </div>

    </div>

</nav>
