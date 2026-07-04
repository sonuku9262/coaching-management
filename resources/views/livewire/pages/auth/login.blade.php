<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>

    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1">🔐 Portal Login</h4>
        <p class="text-muted mb-0">Student • Parent • Teacher • Staff</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login">

        <!-- Email Address -->
        <div class="mb-3">

            <label for="email" class="form-label fw-semibold">Email</label>

            <input wire:model="form.email" id="email" type="email" name="email"
                class="form-control form-control-lg @error('form.email') is-invalid @enderror"
                placeholder="apna email daalein" required autofocus autocomplete="username">

            @error('form.email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <!-- Password -->
        <div class="mb-3">

            <label for="password" class="form-label fw-semibold">Password</label>

            <input wire:model="form.password" id="password" type="password" name="password"
                class="form-control form-control-lg @error('form.password') is-invalid @enderror"
                placeholder="••••••••" required autocomplete="current-password">

            @error('form.password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <!-- Remember Me -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div class="form-check">
                <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                    class="form-check-input">
                <label for="remember" class="form-check-label">Remember me</label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-decoration-none small" href="{{ route('password.request') }}" wire:navigate>
                    Forgot password?
                </a>
            @endif

        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">

            <span wire:loading.remove>Login →</span>

            <span wire:loading>Logging in...</span>

        </button>

    </form>

    <hr class="my-4">

    <p class="text-center text-muted small mb-0">
        Login problem? Institute office se sampark karein.<br>
        Naya student? <a href="/contact" class="text-decoration-none">Admission enquiry karein</a>.
    </p>

</div>
