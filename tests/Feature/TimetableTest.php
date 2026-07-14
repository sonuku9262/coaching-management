<?php

use App\Models\AcademicSession;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Classroom;
use App\Models\ClassTimetable;
use App\Models\Course;
use App\Models\Shift;
use App\Models\StudentRegistration;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherBatchSubject;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function createTimetableFixtures(string $tag = 'TT'): array
{
    $year = AcademicYear::create([
        'name' => '2026-27 ' . $tag,
        'start_date' => '2026-04-01',
        'end_date' => '2027-03-31',
        'status' => true,
    ]);

    $session = AcademicSession::create([
        'academic_year_id' => $year->id,
        'name' => 'Session ' . $tag,
        'start_date' => '2026-04-01',
        'end_date' => '2027-03-31',
        'status' => true,
    ]);

    $course = Course::create([
        'name' => 'Course ' . $tag,
        'code' => 'C-' . $tag,
        'duration' => 1,
        'fees' => 10000,
        'status' => true,
    ]);

    $batch = Batch::create([
        'academic_year_id' => $year->id,
        'academic_session_id' => $session->id,
        'course_id' => $course->id,
        'name' => 'Batch ' . $tag,
        'start_date' => '2026-04-01',
        'end_date' => '2027-03-31',
        'capacity' => 30,
        'status' => true,
    ]);

    $classroom = Classroom::create(['name' => 'Room ' . $tag, 'room_no' => 'R-' . $tag, 'capacity' => 30, 'status' => true]);

    $shift = Shift::create(['name' => 'Morning ' . $tag, 'start_time' => '08:00', 'end_time' => '12:00', 'status' => true]);

    $subject = Subject::create(['course_id' => $course->id, 'name' => 'Subject ' . $tag, 'code' => 'SUB-' . $tag, 'status' => true]);

    return compact('year', 'session', 'course', 'batch', 'classroom', 'shift', 'subject');
}

test('an admin can view the timetable page but a student cannot', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)->get('/admin/timetable')->assertOk();

    $student = User::factory()->create();
    $student->assignRole('student');

    $this->actingAs($student)->get('/admin/timetable')->assertForbidden();
});

test('an admin can add a timetable slot for a batch', function () {
    $fixtures = createTimetableFixtures();
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\Timetable\Index::class)
        ->set('batch_id', $fixtures['batch']->id)
        ->set('subject_id', $fixtures['subject']->id)
        ->set('day_of_week', 'Monday')
        ->set('start_time', '09:00')
        ->set('end_time', '10:00')
        ->call('save');

    $this->assertDatabaseHas('class_timetables', [
        'batch_id' => $fixtures['batch']->id,
        'subject_id' => $fixtures['subject']->id,
        'day_of_week' => 'Monday',
    ]);
});

test('an admin cannot add an overlapping timetable slot for the same batch', function () {
    $fixtures = createTimetableFixtures('OV');
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    ClassTimetable::create([
        'batch_id' => $fixtures['batch']->id,
        'subject_id' => $fixtures['subject']->id,
        'day_of_week' => 'Monday',
        'start_time' => '09:00',
        'end_time' => '10:00',
    ]);

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\Timetable\Index::class)
        ->set('batch_id', $fixtures['batch']->id)
        ->set('subject_id', $fixtures['subject']->id)
        ->set('day_of_week', 'Monday')
        ->set('start_time', '09:30')
        ->set('end_time', '10:30')
        ->call('save')
        ->assertHasErrors(['start_time']);

    expect(ClassTimetable::where('batch_id', $fixtures['batch']->id)->count())->toBe(1);
});

test('a teacher only sees their own assigned timetable slots', function () {
    $fixturesA = createTimetableFixtures('TA');
    $fixturesB = createTimetableFixtures('TB');

    $user = User::factory()->create();
    $user->assignRole('teacher');

    $teacher = Teacher::create([
        'user_id' => $user->id,
        'employee_id' => 'EMP-' . $user->id,
        'name' => 'Timetable Teacher',
        'mobile' => '7777777777',
        'joining_date' => '2026-01-01',
        'status' => true,
    ]);

    TeacherBatchSubject::create([
        'teacher_id' => $teacher->id,
        'batch_id' => $fixturesA['batch']->id,
        'subject_id' => $fixturesA['subject']->id,
    ]);

    ClassTimetable::create([
        'batch_id' => $fixturesA['batch']->id,
        'subject_id' => $fixturesA['subject']->id,
        'teacher_id' => $teacher->id,
        'day_of_week' => 'Monday',
        'start_time' => '09:00',
        'end_time' => '10:00',
    ]);

    // A slot for another batch/teacher — must never show up for this teacher.
    ClassTimetable::create([
        'batch_id' => $fixturesB['batch']->id,
        'subject_id' => $fixturesB['subject']->id,
        'teacher_id' => null,
        'day_of_week' => 'Tuesday',
        'start_time' => '11:00',
        'end_time' => '12:00',
    ]);

    $response = $this->actingAs($user)->get('/teacher/timetable');

    $response->assertOk()
        ->assertSee($fixturesA['batch']->name)
        ->assertDontSee($fixturesB['batch']->name);
});

test('a student only sees their own batchs timetable', function () {
    $fixturesA = createTimetableFixtures('SA');
    $fixturesB = createTimetableFixtures('SB');

    $user = User::factory()->create();
    $user->assignRole('student');

    $student = StudentRegistration::create([
        'user_id' => $user->id,
        'admission_no' => 'ADM-TT-1',
        'academic_year_id' => $fixturesA['year']->id,
        'academic_session_id' => $fixturesA['session']->id,
        'course_id' => $fixturesA['course']->id,
        'batch_id' => $fixturesA['batch']->id,
        'classroom_id' => $fixturesA['classroom']->id,
        'shift_id' => $fixturesA['shift']->id,
        'name' => 'TT Student',
        'father_name' => 'Father',
        'gender' => 'Male',
        'dob' => '2010-01-01',
        'mobile' => '9999999999',
        'address' => 'Address',
        'admission_date' => '2026-04-01',
        'status' => true,
    ]);

    ClassTimetable::create([
        'batch_id' => $fixturesA['batch']->id,
        'subject_id' => $fixturesA['subject']->id,
        'day_of_week' => 'Wednesday',
        'start_time' => '10:00',
        'end_time' => '11:00',
    ]);

    ClassTimetable::create([
        'batch_id' => $fixturesB['batch']->id,
        'subject_id' => $fixturesB['subject']->id,
        'day_of_week' => 'Thursday',
        'start_time' => '10:00',
        'end_time' => '11:00',
    ]);

    $response = $this->actingAs($user)->get('/student/timetable');

    $response->assertOk()
        ->assertSee($fixturesA['subject']->name)
        ->assertDontSee($fixturesB['subject']->name);
});
