<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Coaching Management System</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body>

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

</body>

</html>