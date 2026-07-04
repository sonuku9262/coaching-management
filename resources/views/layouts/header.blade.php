<nav class="navbar navbar-dark bg-primary">

    <div class="container-fluid">

        <a class="navbar-brand">
            {{ \App\Models\Setting::get('institute_name', 'Coaching Management System') }}
        </a>

        <div class="d-flex align-items-center gap-3">

            <span class="text-white">
                {{ auth()->user()->name }}
            </span>

            <a href="{{ route('profile') }}" class="btn btn-outline-light btn-sm">
                Profile
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-light btn-sm">
                    Logout
                </button>
            </form>

        </div>

    </div>

</nav>
