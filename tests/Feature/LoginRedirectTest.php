<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Volt\Volt;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('a student logging in lands on the student portal', function () {
    $user = User::factory()->create();
    $user->assignRole('student');

    Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('student.dashboard', absolute: false));
});

test('a teacher logging in lands on the teacher portal', function () {
    $user = User::factory()->create();
    $user->assignRole('teacher');

    Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('teacher.dashboard', absolute: false));
});

test('a parent logging in lands on the parent portal', function () {
    $user = User::factory()->create();
    $user->assignRole('parent');

    Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('parent.dashboard', absolute: false));
});

test('an admin logging in lands on the admin dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));
});

test('a user who is both teacher and admin lands on the admin dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole(['teacher', 'admin']);

    expect($user->dashboardRoute())->toBe('dashboard');
});

test('a user without any role is parked on the public site', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect(route('home'));
});

test('a deactivated user cannot log in', function () {
    $user = User::factory()->create(['status' => false]);
    $user->assignRole('student');

    Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasErrors();

    $this->assertGuest();
});
