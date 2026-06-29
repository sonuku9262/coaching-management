<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Livewire\Admin\Role\Index;
use App\Livewire\Admin\User\Index as UserIndex;
use App\Livewire\Admin\MasterData\Course\Index as CourseIndex;
use App\Livewire\Admin\MasterData\AcademicYear\Index as AcademicYearIndex;
use App\Livewire\Admin\MasterData\AcademicSession\Index as AcademicSessionIndex;
use App\Livewire\Admin\MasterData\Subject\Index as SubjectIndex;
use App\Livewire\Admin\MasterData\Batch\Index as BatchIndex;
use App\Livewire\Admin\MasterData\Classroom\Index as ClassroomIndex;
use App\Livewire\Admin\MasterData\Shift\Index as ShiftIndex;
use App\Livewire\Admin\StudentManagement\StudentRegistration\Index as StudentRegistrationIndex;
use App\Livewire\Admin\FeeManagement\FeeType\Index as FeeTypeIndex;
use App\Livewire\Admin\FeeManagement\FeeStructure\Index as FeeStructureIndex;
use App\Livewire\Admin\FeeManagement\FeeCollection\Index as FeeCollectionIndex;
use App\Livewire\Admin\TeacherManagement\Teacher\Index as TeacherIndex;
use App\Livewire\Admin\Permission\Index as PermissionIndex;


Route::view('/', 'welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
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

     Route::get('/academic-years', AcademicYearIndex::class)
        ->name('admin.academic-years.index');

     Route::get('/academic-sessions', AcademicSessionIndex::class)
        ->name('admin.academic-sessions.index');

     Route::get('/subjects', SubjectIndex::class)
        ->name('admin.subjects.index');

     Route::get('/batches', BatchIndex::class)
        ->name('admin.batches.index');

    Route::get('/classrooms', ClassroomIndex::class)
    ->name('admin.classrooms.index');

    Route::get('/shifts', ShiftIndex::class)
    ->name('admin.shifts.index');

     Route::get('/student-registrations', StudentRegistrationIndex::class)
        ->name('admin.student-registrations.index');

     Route::get('/fee-types', FeeTypeIndex::class)
        ->name('admin.fee-types.index');

    Route::get('/fee-structures', FeeStructureIndex::class)
    ->name('admin.fee-structures.index');

    Route::get('/fee-collections', FeeCollectionIndex::class)
    ->name('admin.fee-collections.index');

    Route::get('/teachers', TeacherIndex::class)
        ->name('admin.teachers.index');

    Route::get('/permissions', PermissionIndex::class)
    ->name('admin.permissions.index');

});


Route::view('/', 'frontend.home')->name('home');
Route::view('/about', 'frontend.about');
Route::view('/courses', 'frontend.courses');
Route::view('/gallery', 'frontend.gallery');
Route::view('/contact', 'frontend.contact');


require __DIR__ . '/auth.php';
