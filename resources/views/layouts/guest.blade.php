<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ \App\Models\Setting::get('institute_name', config('app.name', 'Coaching ERP')) }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .auth-bg {
                min-height: 100vh;
                background: linear-gradient(135deg, #0d6efd 0%, #0a4bb5 60%, #063a8f 100%);
            }
        </style>
    </head>
    <body>

        <div class="auth-bg d-flex flex-column align-items-center justify-content-center py-5 px-3">

            <a href="/" wire:navigate class="text-decoration-none text-center mb-4">

                @if(\App\Models\Setting::get('institute_logo'))
                    <img src="{{ asset('storage/' . \App\Models\Setting::get('institute_logo')) }}"
                        height="60" class="bg-white rounded p-2 mb-2 d-block mx-auto">
                @endif

                <h3 class="text-white fw-bold mb-0">
                    {{ \App\Models\Setting::get('institute_name', 'Coaching ERP') }}
                </h3>

            </a>

            <div class="card border-0 shadow-lg rounded-4" style="width: 100%; max-width: 440px;">

                <div class="card-body p-4">
                    {{ $slot }}
                </div>

            </div>

            <a href="/" wire:navigate class="text-white-50 text-decoration-none mt-4">
                ← Back to Website
            </a>

        </div>

    </body>
</html>
