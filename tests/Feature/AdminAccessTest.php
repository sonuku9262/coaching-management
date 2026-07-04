<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('guests are redirected to login from admin pages', function () {
    $this->get('/admin/courses')->assertRedirect('/login');
});

test('a user without permission gets 403 on admin pages', function () {
    $user = User::factory()->create();
    $user->assignRole('student');

    $this->actingAs($user)
        ->get('/admin/courses')
        ->assertForbidden();
});

test('an admin can view admin pages', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get('/admin/courses')
        ->assertOk();
});

test('a super admin can view every admin page', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $this->actingAs($user)->get('/admin/roles')->assertOk();
    $this->actingAs($user)->get('/admin/users')->assertOk();
    $this->actingAs($user)->get('/admin/courses')->assertOk();
    $this->actingAs($user)->get('/admin/settings')->assertOk();
    $this->actingAs($user)->get('/admin/activity-log')->assertOk();
    $this->actingAs($user)->get('/admin/reports/dues')->assertOk();
    $this->actingAs($user)->get('/admin/exam-report-card')->assertOk();
});

test('an accountant can view fees but not user management', function () {
    $user = User::factory()->create();
    $user->assignRole('accountant');

    $this->actingAs($user)->get('/admin/fee-collections')->assertOk();
    $this->actingAs($user)->get('/admin/users')->assertForbidden();
});

test('a teacher can view exams and enter marks pages', function () {
    $user = User::factory()->create();
    $user->assignRole('teacher');

    $this->actingAs($user)->get('/admin/exams')->assertOk();
    $this->actingAs($user)->get('/admin/exam-results')->assertOk();
});

test('a teacher can view student attendance but not fees', function () {
    $user = User::factory()->create();
    $user->assignRole('teacher');

    $this->actingAs($user)->get('/admin/student-attendance')->assertOk();
    $this->actingAs($user)->get('/admin/fee-collections')->assertForbidden();
});
