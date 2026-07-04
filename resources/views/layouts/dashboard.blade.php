<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ \App\Models\Setting::get('institute_name', 'Coaching Management System') }} — Dashboard</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body class="admin-body">

    @include('layouts.header')

    <div class="container-fluid">

        <div class="row">

            @include('layouts.sidebar')

            <main class="col-md-10 p-4">

                @yield('content')

            </main>

        </div>

    </div>

    @include('layouts.footer')

    @stack('scripts')

</body>

</html>
