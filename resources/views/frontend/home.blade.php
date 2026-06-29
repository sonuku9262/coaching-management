@extends('layouts.frontend')

@section('content')
    <section class="bg-primary text-white py-5">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h1 class="display-4 fw-bold">
                        Welcome to Coaching Management System
                    </h1>

                    <p class="lead mt-3">
                        Manage Students, Teachers, Courses, Fees,
                        Attendance and Exams in one place.
                    </p>

                    <a href="/login" class="btn btn-light btn-lg mt-3">
                        Get Started
                    </a>

                </div>

                <div class="col-md-6 text-center">

                    <img src="https://picsum.photos/600/400" class="img-fluid rounded shadow">

                </div>

            </div>

        </div>

        <!-- About Section -->

        <section class="py-5">

            <div class="container">

                <div class="row align-items-center">

                    <div class="col-lg-6">

                        <img src="https://picsum.photos/600/400" class="img-fluid rounded shadow">

                    </div>

                    <div class="col-lg-6">

                        <h2 class="fw-bold mb-3">
                            About Our Coaching
                        </h2>

                        <p class="text-muted">
                            We provide quality education with experienced teachers,
                            modern classrooms, online learning support, attendance,
                            fee management and student progress tracking.
                        </p>

                        <ul class="list-group list-group-flush">

                            <li class="list-group-item">
                                ✅ Experienced Teachers
                            </li>

                            <li class="list-group-item">
                                ✅ Smart Classrooms
                            </li>

                            <li class="list-group-item">
                                ✅ Online Attendance
                            </li>

                            <li class="list-group-item">
                                ✅ Result Management
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </section>

        <section class="bg-light py-5">

            <div class="container">

                <div class="text-center mb-5">

                    <h2>Why Choose Us</h2>

                </div>

                <div class="row">

                    <div class="col-md-3">

                        <div class="card shadow text-center">

                            <div class="card-body">

                                <h1>👨‍🏫</h1>

                                <h5>Expert Faculty</h5>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card shadow text-center">

                            <div class="card-body">

                                <h1>📚</h1>

                                <h5>Quality Courses</h5>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card shadow text-center">

                            <div class="card-body">

                                <h1>💻</h1>

                                <h5>Online Classes</h5>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card shadow text-center">

                            <div class="card-body">

                                <h1>🏆</h1>

                                <h5>Best Results</h5>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <!-- Popular Courses -->

        <section class="py-5">

            <div class="container">

                <div class="text-center mb-5">

                    <h2 class="fw-bold">Popular Courses</h2>

                    <p class="text-muted">
                        Explore our most popular courses.
                    </p>

                </div>

                <div class="row">

                    <div class="col-md-3 mb-4">

                        <div class="card shadow h-100">

                            <div class="card-body text-center">

                                <h1>💻</h1>

                                <h5>BCA</h5>

                                <p>
                                    Bachelor of Computer Applications
                                </p>

                                <a href="#" class="btn btn-primary">
                                    View Details
                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3 mb-4">

                        <div class="card shadow h-100">

                            <div class="card-body text-center">

                                <h1>📊</h1>

                                <h5>BBA</h5>

                                <p>
                                    Bachelor of Business Administration
                                </p>

                                <a href="#" class="btn btn-primary">
                                    View Details
                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3 mb-4">

                        <div class="card shadow h-100">

                            <div class="card-body text-center">

                                <h1>🎓</h1>

                                <h5>MCA</h5>

                                <p>
                                    Master of Computer Applications
                                </p>

                                <a href="#" class="btn btn-primary">
                                    View Details
                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3 mb-4">

                        <div class="card shadow h-100">

                            <div class="card-body text-center">

                                <h1>🖥️</h1>

                                <h5>DCA</h5>

                                <p>
                                    Diploma in Computer Applications
                                </p>

                                <a href="#" class="btn btn-primary">
                                    View Details
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <!-- Our Teachers -->

        <section class="bg-light py-5">

            <div class="container">

                <div class="text-center mb-5">

                    <h2 class="fw-bold">Meet Our Expert Teachers</h2>

                    <p class="text-muted">
                        Learn from experienced and dedicated faculty members.
                    </p>

                </div>

                <div class="row">

                    <div class="col-lg-3 col-md-6 mb-4">

                        <div class="card shadow border-0 h-100">

                            <img src="https://picsum.photos/300/300?random=1" class="card-img-top">

                            <div class="card-body text-center">

                                <h5 class="fw-bold">Rahul Kumar</h5>

                                <p class="text-muted">PHP & Laravel Trainer</p>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">

                        <div class="card shadow border-0 h-100">

                            <img src="https://picsum.photos/300/300?random=2" class="card-img-top">

                            <div class="card-body text-center">

                                <h5 class="fw-bold">Amit Singh</h5>

                                <p class="text-muted">Java Faculty</p>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">

                        <div class="card shadow border-0 h-100">

                            <img src="https://picsum.photos/300/300?random=3" class="card-img-top">

                            <div class="card-body text-center">

                                <h5 class="fw-bold">Neha Sharma</h5>

                                <p class="text-muted">Python Faculty</p>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">

                        <div class="card shadow border-0 h-100">

                            <img src="https://picsum.photos/300/300?random=4" class="card-img-top">

                            <div class="card-body text-center">

                                <h5 class="fw-bold">Priya Verma</h5>

                                <p class="text-muted">Web Development</p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <!-- Statistics -->

        <section class="py-5 bg-primary text-white">

            <div class="container">

                <div class="row text-center">

                    <div class="col-md-3">

                        <h1>5000+</h1>

                        <h5>Students</h5>

                    </div>

                    <div class="col-md-3">

                        <h1>100+</h1>

                        <h5>Teachers</h5>

                    </div>

                    <div class="col-md-3">

                        <h1>50+</h1>

                        <h5>Courses</h5>

                    </div>

                    <div class="col-md-3">

                        <h1>98%</h1>

                        <h5>Success Rate</h5>

                    </div>

                </div>

            </div>

        </section>

    </section>
@endsection
