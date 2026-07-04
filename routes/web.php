<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Livewire\Admin\Role\Index as RoleIndex;
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
use App\Livewire\Admin\Attendance\StudentAttendance\Index as StudentAttendanceIndex;
use App\Livewire\Admin\Attendance\TeacherAttendance\Index as TeacherAttendanceIndex;
use App\Livewire\Admin\Examination\Exam\Index as ExamIndex;
use App\Livewire\Admin\Examination\Result\Index as ExamResultIndex;
use App\Livewire\Admin\Reports\FeeReport;
use App\Livewire\Admin\Reports\AttendanceReport;
use App\Livewire\Admin\Reports\DuesReport;
use App\Livewire\Admin\Settings\Index as SettingsIndex;
use App\Livewire\Admin\ActivityLog\Index as ActivityLogIndex;
use App\Livewire\Admin\Examination\ReportCard;
use App\Livewire\Portal\Teacher\Dashboard as TeacherDashboard;
use App\Livewire\Portal\Student\Dashboard as StudentDashboard;
use App\Livewire\Portal\ParentPortal\Dashboard as ParentDashboard;

/*
|--------------------------------------------------------------------------
| Public / Frontend
|--------------------------------------------------------------------------
*/

Route::view('/', 'frontend.home')->name('home');
Route::view('/about', 'frontend.about');
Route::view('/courses', 'frontend.courses');
Route::view('/gallery', 'frontend.gallery');
Route::view('/contact', 'frontend.contact');

/*
|--------------------------------------------------------------------------
| Authenticated
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('logout', function (\App\Livewire\Actions\Logout $logout) {
    $logout();

    return redirect('/');
})->middleware(['auth'])->name('logout');

/*
|--------------------------------------------------------------------------
| Portals (role protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', TeacherDashboard::class)->name('teacher.dashboard');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', StudentDashboard::class)->name('student.dashboard');
});

Route::middleware(['auth', 'role:parent'])->group(function () {
    Route::get('/parent/dashboard', ParentDashboard::class)->name('parent.dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin panel (permission protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // User Management
    Route::get('/roles', RoleIndex::class)
        ->middleware('permission:roles.view')
        ->name('roles.index');

    Route::get('/users', UserIndex::class)
        ->middleware('permission:users.view')
        ->name('users.index');

    Route::get('/permissions', PermissionIndex::class)
        ->middleware('permission:permissions.view')
        ->name('permissions.index');

    // Master Data
    Route::get('/academic-years', AcademicYearIndex::class)
        ->middleware('permission:academic-years.view')
        ->name('academic-years.index');

    Route::get('/academic-sessions', AcademicSessionIndex::class)
        ->middleware('permission:academic-sessions.view')
        ->name('academic-sessions.index');

    Route::get('/courses', CourseIndex::class)
        ->middleware('permission:courses.view')
        ->name('courses.index');

    Route::get('/subjects', SubjectIndex::class)
        ->middleware('permission:subjects.view')
        ->name('subjects.index');

    Route::get('/batches', BatchIndex::class)
        ->middleware('permission:batches.view')
        ->name('batches.index');

    Route::get('/classrooms', ClassroomIndex::class)
        ->middleware('permission:classrooms.view')
        ->name('classrooms.index');

    Route::get('/shifts', ShiftIndex::class)
        ->middleware('permission:shifts.view')
        ->name('shifts.index');

    // Student Management
    Route::get('/student-registrations', StudentRegistrationIndex::class)
        ->middleware('permission:students.view')
        ->name('student-registrations.index');

    // Teacher Management
    Route::get('/teachers', TeacherIndex::class)
        ->middleware('permission:teachers.view')
        ->name('teachers.index');

    // Fee Management
    Route::get('/fee-types', FeeTypeIndex::class)
        ->middleware('permission:fee-types.view')
        ->name('fee-types.index');

    Route::get('/fee-structures', FeeStructureIndex::class)
        ->middleware('permission:fee-structures.view')
        ->name('fee-structures.index');

    Route::get('/fee-collections', FeeCollectionIndex::class)
        ->middleware('permission:fee-collections.view')
        ->name('fee-collections.index');

    // Examination
    Route::get('/exams', ExamIndex::class)
        ->middleware('permission:exams.view')
        ->name('exams.index');

    Route::get('/exam-results', ExamResultIndex::class)
        ->middleware('permission:exam-results.view')
        ->name('exam-results.index');

    // Attendance
    Route::get('/student-attendance', StudentAttendanceIndex::class)
        ->middleware('permission:student-attendance.view')
        ->name('student-attendance.index');

    Route::get('/teacher-attendance', TeacherAttendanceIndex::class)
        ->middleware('permission:teacher-attendance.view')
        ->name('teacher-attendance.index');

    // Reports
    Route::get('/reports/fees', FeeReport::class)
        ->middleware('permission:reports.view')
        ->name('reports.fees');

    Route::get('/reports/attendance', AttendanceReport::class)
        ->middleware('permission:reports.view')
        ->name('reports.attendance');

    Route::get('/reports/dues', DuesReport::class)
        ->middleware('permission:reports.view')
        ->name('reports.dues');

    // Report Card
    Route::get('/exam-report-card', ReportCard::class)
        ->middleware('permission:exam-results.view')
        ->name('exam-report-card');

    // Settings
    Route::get('/settings', SettingsIndex::class)
        ->middleware('permission:settings.view')
        ->name('settings.index');

    // Activity Log
    Route::get('/activity-log', ActivityLogIndex::class)
        ->middleware('permission:activity-logs.view')
        ->name('activity-log.index');

});

require __DIR__ . '/auth.php';
