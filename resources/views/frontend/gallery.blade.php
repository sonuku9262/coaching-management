@extends('layouts.frontend')

@section('title', 'Gallery — ' . \App\Models\Setting::get('institute_name', 'Coaching Institute'))

@section('content')

    <!-- Page Header -->
    <section class="hero-section text-white py-5">
        <div class="container text-center">
            <h1 class="fw-bold">Gallery</h1>
            <p class="lead text-white-50 mb-0">Hamare institute ki jhalkiyan</p>
        </div>
    </section>

    <section class="py-5">

        <div class="container">

            @if($images->isNotEmpty())

                <div class="row g-4">

                    @foreach($images as $image)

                        <div class="col-lg-4 col-md-6">

                            <div class="card border-0 shadow hover-lift overflow-hidden">
                                <img src="{{ $image }}" class="img-fluid" style="height: 260px; object-fit: cover;">
                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-center text-muted py-5">

                    <h1 class="display-1">📸</h1>

                    <h4>Photos jald hi upload hongi.</h4>

                    <p>Admin panel me courses ki images add karein — woh yahan automatically dikhengi.</p>

                </div>

            @endif

        </div>

    </section>

@endsection
