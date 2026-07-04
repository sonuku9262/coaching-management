@extends('layouts.frontend')

@section('title', 'Gallery — ' . \App\Models\Setting::get('institute_name', 'Coaching Institute'))

@section('content')

    <!-- Page Header -->
    <section class="hero-section text-white py-5">
        <span class="floating-shape" style="width: 110px; height: 110px; top: 12%; left: 5%;"></span>
        <span class="floating-shape float-fast" style="width: 70px; height: 70px; bottom: 14%; right: 8%;"></span>
        <div class="container text-center position-relative">
            <h1 class="fw-bold hero-enter hero-enter-1">Gallery</h1>
            <p class="lead text-white-50 mb-0 hero-enter hero-enter-2">Hamare institute ki jhalkiyan</p>
        </div>
    </section>

    <section class="py-5">

        <div class="container">

            @if($photos->isNotEmpty())

                <div class="row g-4">

                    @foreach($photos as $photo)

                        <div class="col-lg-4 col-md-6 reveal-zoom {{ 'delay-' . ($loop->index % 3) }}">

                            <div class="card border-0 shadow hover-lift overflow-hidden h-100 gallery-item">

                                <img src="{{ $photo['url'] }}" class="img-fluid" style="height: 260px; object-fit: cover;">

                                @if($photo['title'])
                                    <div class="card-body py-2 text-center">
                                        <small class="fw-semibold">{{ $photo['title'] }}</small>
                                    </div>
                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-center text-muted py-5">

                    <h1 class="display-1">📸</h1>

                    <h4>Photos jald hi upload hongi.</h4>

                    <p>Admin panel me <strong>Website → Gallery</strong> se photos add karein — woh yahan automatically dikhengi.</p>

                </div>

            @endif

        </div>

    </section>

@endsection
