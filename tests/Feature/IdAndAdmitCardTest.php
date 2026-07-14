<?php

use App\Models\AcademicSession;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\Shift;
use App\Models\StudentRegistration;
use App\Models\Subject;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function createCardFixtures(string $tag = 'CARD'): array
{
    $year = AcademicYear::create(['name' => 'Card-Year-' . $tag, 'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'status' => true]);
    $session = AcademicSession::create(['academic_year_id' => $year->id, 'name' => 'Card-Session-' . $tag, 'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'status' => true]);
    $course = Course::create(['name' => 'Card Course ' . $tag, 'code' => 'CARD-' . $tag, 'duration' => 1, 'fees' => 10000, 'status' => true]);
    $batch = Batch::create(['academic_year_id' => $year->id, 'academic_session_id' => $session->id, 'course_id' => $course->id, 'name' => 'Card Batch ' . $tag, 'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'capacity' => 30, 'status' => true]);
    $classroom = Classroom::create(['name' => 'Card Room ' . $tag, 'room_no' => 'CARD-' . $tag, 'capacity' => 30, 'status' => true]);
    $shift = Shift::create(['name' => 'Card Shift ' . $tag, 'start_time' => '08:00', 'end_time' => '12:00', 'status' => true]);
    $subject = Subject::create(['course_id' => $course->id, 'name' => 'Card Subject ' . $tag, 'code' => 'CS-' . $tag, 'status' => true]);

    $student = StudentRegistration::create([
        'admission_no' => 'ADM-CARD-' . $tag,
        'academic_year_id' => $year->id,
        'academic_session_id' => $session->id,
        'course_id' => $course->id,
        'batch_id' => $batch->id,
        'classroom_id' => $classroom->id,
        'shift_id' => $shift->id,
        'name' => 'Card Student ' . $tag,
        'father_name' => 'Father ' . $tag,
        'gender' => 'Male',
        'dob' => '2010-01-01',
        'mobile' => '9999999999',
        'address' => 'Address',
        'admission_date' => '2026-04-01',
        'status' => true,
    ]);

    return compact('year', 'session', 'course', 'batch', 'classroom', 'shift', 'subject', 'student');
}

test('an admin can view id card and admit card pages but a student cannot', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)->get('/admin/id-cards')->assertOk();
    $this->actingAs($admin)->get('/admin/admit-cards')->assertOk();

    $student = User::factory()->create();
    $student->assignRole('student');

    $this->actingAs($student)->get('/admin/id-cards')->assertForbidden();
    $this->actingAs($student)->get('/admin/admit-cards')->assertForbidden();
});

test('id card page only shows students from the selected batch', function () {
    $fixtures = createCardFixtures('IDA');
    $otherFixtures = createCardFixtures('IDB');

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\IdCard\Index::class)
        ->set('course_id', $fixtures['course']->id)
        ->set('batch_id', $fixtures['batch']->id)
        ->assertSee($fixtures['student']->name)
        ->assertSee($fixtures['student']->admission_no)
        ->assertDontSee($otherFixtures['student']->name);
});

test('admit card page shows the full exam schedule for the selected exam and only that batchs students', function () {
    $fixtures = createCardFixtures('ADM1');
    $otherFixtures = createCardFixtures('ADM2');

    $exam = Exam::create([
        'name' => 'Card Test Exam',
        'course_id' => $fixtures['course']->id,
        'batch_id' => $fixtures['batch']->id,
        'status' => true,
    ]);

    ExamSchedule::create([
        'exam_id' => $exam->id,
        'subject_id' => $fixtures['subject']->id,
        'exam_date' => '2026-08-01',
        'start_time' => '10:00',
        'end_time' => '12:00',
        'total_marks' => 100,
        'passing_marks' => 33,
    ]);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\Examination\AdmitCard::class)
        ->set('exam_id', $exam->id)
        ->assertSee('Card Test Exam')
        ->assertSee($fixtures['student']->name)
        ->assertSee($fixtures['subject']->name)
        ->assertSee('100')
        ->assertDontSee($otherFixtures['student']->name);
});
