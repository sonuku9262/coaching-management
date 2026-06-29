<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Role\Index;
use App\Livewire\Admin\User\Index as UserIndex;
use App\Livewire\Admin\Course\Index as CourseIndex;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {

    Route::get('/roles', Index::class)->name('roles.index');
    Route::get('/users', UserIndex::class)->name('users.index');



});

Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/courses', CourseIndex::class)
        ->name('admin.courses.index');

});


Route::view('/', 'frontend.home')->name('home');
Route::view('/about', 'frontend.about');
Route::view('/courses', 'frontend.courses');
Route::view('/gallery', 'frontend.gallery');
Route::view('/contact', 'frontend.contact');


require __DIR__ . '/auth.php';
