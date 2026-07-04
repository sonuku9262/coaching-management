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

            @if($photos->isNotEmpty())

                <div class="row g-4">

                    @foreach($photos as $photo)

                        <div class="col-lg-4 col-md-6">

                            <div class="card border-0 shadow hover-lift overflow-hidden h-100">

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
