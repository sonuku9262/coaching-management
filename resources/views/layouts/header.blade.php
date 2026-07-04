<nav class="navbar navbar-dark admin-topbar">

    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="/dashboard">
            {{ \App\Models\Setting::get('institute_name', 'Coaching Management System') }}
        </a>

        <div class="d-flex align-items-center gap-3">

            <a href="/" target="_blank" class="btn btn-outline-light btn-sm d-none d-md-inline-block">
                🌐 View Website
            </a>

            <div class="dropdown">

                <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-2"
                    data-bs-toggle="dropdown">

                    <span class="fw-semibold">{{ auth()->user()->name }}</span>

                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow">

                    <li>
                        <span class="dropdown-item-text small text-muted">
                            {{ auth()->user()->email }}<br>
                            @foreach(auth()->user()->roles as $role)
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            @endforeach
                        </span>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item" href="{{ route('profile') }}">
                            👤 My Profile
                        </a>
                    </li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                🚪 Logout
                            </button>
                        </form>
                    </li>

                </ul>

            </div>

        </div>

    </div>

</nav>
