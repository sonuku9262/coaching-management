<footer class="bg-dark text-white mt-5 pt-5">

    <div class="container">

        <div class="row pb-4">

            <div class="col-md-4 mb-4">

                <h5 class="fw-bold mb-3">
                    {{ \App\Models\Setting::get('institute_name', 'Coaching Management System') }}
                </h5>

                <p class="text-white-50 mb-2">
                    {{ \App\Models\Setting::get('institute_address', 'Quality education with expert faculty and proven results.') }}
                </p>

                @if(\App\Models\Setting::get('institute_phone'))
                    <p class="mb-1">📞 {{ \App\Models\Setting::get('institute_phone') }}</p>
                @endif

                @if(\App\Models\Setting::get('institute_email'))
                    <p class="mb-1">✉️ {{ \App\Models\Setting::get('institute_email') }}</p>
                @endif

            </div>

            <div class="col-md-2 mb-4">

                <h6 class="fw-bold mb-3">Quick Links</h6>

                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="/about" class="text-white-50 text-decoration-none">About Us</a></li>
                    <li class="mb-2"><a href="/courses" class="text-white-50 text-decoration-none">Courses</a></li>
                    <li class="mb-2"><a href="/gallery" class="text-white-50 text-decoration-none">Gallery</a></li>
                    <li class="mb-2"><a href="/contact" class="text-white-50 text-decoration-none">Contact</a></li>
                </ul>

            </div>

            <div class="col-md-3 mb-4">

                <h6 class="fw-bold mb-3">Portals</h6>

                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/login" class="text-white-50 text-decoration-none">🎓 Student Login</a></li>
                    <li class="mb-2"><a href="/login" class="text-white-50 text-decoration-none">👨‍👩‍👦 Parent Login</a></li>
                    <li class="mb-2"><a href="/login" class="text-white-50 text-decoration-none">👨‍🏫 Teacher Login</a></li>
                    <li class="mb-2"><a href="/login" class="text-white-50 text-decoration-none">🛡️ Admin Login</a></li>
                </ul>

            </div>

            <div class="col-md-3 mb-4">

                <h6 class="fw-bold mb-3">Admissions Open</h6>

                <p class="text-white-50">
                    Naye batch me admission ke liye aaj hi enquiry karein.
                </p>

                <a href="/contact" class="btn btn-warning fw-bold">
                    📝 Enquiry Now
                </a>

            </div>

        </div>

    </div>

    <div class="border-top border-secondary py-3 text-center text-white-50">

        © {{ date('Y') }} {{ \App\Models\Setting::get('institute_name', 'Coaching Management System') }} — All Rights Reserved

    </div>

</footer>
