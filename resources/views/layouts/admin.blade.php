<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ \App\Models\Setting::get('institute_name', 'Coaching Management System') }} — Admin</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    @livewireStyles
</head>

<body class="admin-body">

    @include('layouts.header')

    <div class="container-fluid">
        <div class="row">

            @include('layouts.sidebar')

            <main class="col-md-10 p-4">

                {{ $slot }}

            </main>

        </div>
    </div>

    @include('layouts.footer')

    @livewireScripts

    @stack('scripts')

</body>

</html>
