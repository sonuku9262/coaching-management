<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', \App\Models\Setting::get('institute_name', 'Coaching Management System'))</title>

    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('institute_name', 'Coaching Institute') . ' — quality coaching with expert faculty, smart classrooms and proven results.')">

    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        .hero-section {
            background: linear-gradient(135deg, #0d6efd 0%, #0a4bb5 60%, #063a8f 100%);
        }

        .hover-lift {
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, .15) !important;
        }

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
        }
    </style>

    @stack('styles')
</head>

<body>

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')

</body>

</html>
