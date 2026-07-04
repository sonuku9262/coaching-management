<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('a teacher lands on the teacher portal from dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('teacher');

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect(route('teacher.dashboard'));

    $this->actingAs($user)
        ->get('/teacher/dashboard')
        ->assertOk();
});

test('a student lands on the student portal from dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('student');

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect(route('student.dashboard'));

    $this->actingAs($user)
        ->get('/student/dashboard')
        ->assertOk();
});

test('a parent lands on the parent portal from dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('parent');

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect(route('parent.dashboard'));

    $this->actingAs($user)
        ->get('/parent/dashboard')
        ->assertOk();
});

test('a student cannot open the teacher portal', function () {
    $user = User::factory()->create();
    $user->assignRole('student');

    $this->actingAs($user)
        ->get('/teacher/dashboard')
        ->assertForbidden();
});

test('an admin stays on the admin dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Dashboard');
});
