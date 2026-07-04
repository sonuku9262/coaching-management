<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', \App\Models\Setting::get('institute_name', 'Coaching Management System'))</title>

    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('institute_name', 'Coaching Institute') . ' — quality coaching with expert faculty, smart classrooms and proven results.')">

    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        /* ---------- hero ---------- */

        .hero-section {
            background: linear-gradient(135deg, #0d6efd, #0a4bb5, #063a8f, #1d4ed8);
            background-size: 300% 300%;
            animation: gradientShift 12s ease infinite;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .hero-section .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
            animation: floaty 7s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes floaty {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-24px) rotate(6deg); }
        }

        .float-slow { animation-duration: 10s; }
        .float-fast { animation-duration: 5s; }

        .hero-illustration {
            animation: floaty 8s ease-in-out infinite;
            max-width: 100%;
        }

        /* ---------- entrance animations (hero, on load) ---------- */

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(34px); }
            to { opacity: 1; transform: none; }
        }

        .hero-enter { animation: fadeUp .8s ease both; }
        .hero-enter-1 { animation-delay: .1s; }
        .hero-enter-2 { animation-delay: .25s; }
        .hero-enter-3 { animation-delay: .4s; }
        .hero-enter-4 { animation-delay: .55s; }

        /* ---------- scroll reveal ---------- */

        .reveal,
        .reveal-left,
        .reveal-right,
        .reveal-zoom {
            opacity: 0;
            transition: opacity .7s ease, transform .7s ease;
            will-change: opacity, transform;
        }

        .reveal { transform: translateY(36px); }
        .reveal-left { transform: translateX(-46px); }
        .reveal-right { transform: translateX(46px); }
        .reveal-zoom { transform: scale(.88); }

        .revealed {
            opacity: 1;
            transform: none;
        }

        .delay-1 { transition-delay: .12s; }
        .delay-2 { transition-delay: .24s; }
        .delay-3 { transition-delay: .36s; }
        .delay-4 { transition-delay: .48s; }

        @media (prefers-reduced-motion: reduce) {
            .reveal, .reveal-left, .reveal-right, .reveal-zoom, .hero-enter {
                animation: none !important;
                transition: none !important;
                opacity: 1;
                transform: none;
            }
        }

        /* ---------- cards & misc ---------- */

        .hover-lift {
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 1.1rem 2.2rem rgba(13, 42, 110, .18) !important;
        }

        .hover-lift .card-img-top,
        .hover-lift .course-thumb {
            transition: transform .4s ease;
        }

        .hover-lift:hover .card-img-top,
        .hover-lift:hover .course-thumb {
            transform: scale(1.05);
        }

        .img-zoom-wrap { overflow: hidden; }

        .section-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: #0d6efd;
            border-radius: 2px;
            margin: .5rem auto 0;
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.8rem;
            margin: 0 auto;
            transition: transform .3s ease;
        }

        .card:hover .icon-circle {
            transform: rotate(-8deg) scale(1.12);
        }

        .btn-pulse {
            animation: pulse 2.2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, .55); }
            70% { box-shadow: 0 0 0 16px rgba(255, 193, 7, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
        }

        .gallery-item img {
            transition: transform .45s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.08) rotate(.5deg);
        }
    </style>

    @stack('styles')
</head>

<body>

    @include('partials.navbar')

    @if(session('info'))
        <div class="alert alert-warning text-center mb-0 rounded-0">
            {{ session('info') }}
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <script>
        // scroll reveal
        const revealTargets = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-zoom');

        if (! ('IntersectionObserver' in window)) {
            revealTargets.forEach((el) => el.classList.add('revealed'));
        }

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        revealTargets.forEach((el) => revealObserver.observe(el));

        // stat counters
        const counters = document.querySelectorAll('[data-count]');

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (! entry.isIntersecting) return;

                const el = entry.target;
                const target = parseInt(el.dataset.count, 10) || 0;
                const suffix = el.dataset.suffix || '';
                const duration = 1300;
                const start = performance.now();

                const tick = (now) => {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.round(eased * target).toLocaleString() + suffix;

                    if (progress < 1) requestAnimationFrame(tick);
                };

                requestAnimationFrame(tick);
                counterObserver.unobserve(el);
            });
        }, { threshold: 0.4 });

        counters.forEach((el) => counterObserver.observe(el));
    </script>

    @stack('scripts')

</body>

</html>
