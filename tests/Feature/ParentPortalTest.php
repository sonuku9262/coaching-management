<?php

use App\Models\AcademicSession;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\FeeCollection;
use App\Models\FeeType;
use App\Models\Shift;
use App\Models\StudentAttendance;
use App\Models\StudentRegistration;
use App\Models\Subject;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

/**
 * Builds a full course/batch chain and a student registration guarded by
 * the given guardian, so parent-portal tests have real data to scope to.
 */
function createGuardedStudent(?int $guardianUserId, string $tag, string $name): StudentRegistration
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
        'name' => 'Science ' . $tag,
        'code' => 'SCI-' . $tag,
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

    return StudentRegistration::create([
        'guardian_user_id' => $guardianUserId,
        'admission_no' => 'ADM-' . $tag,
        'academic_year_id' => $year->id,
        'academic_session_id' => $session->id,
        'course_id' => $course->id,
        'batch_id' => $batch->id,
        'classroom_id' => $classroom->id,
        'shift_id' => $shift->id,
        'name' => $name,
        'father_name' => 'Father ' . $tag,
        'gender' => 'Male',
        'dob' => '2010-01-01',
        'mobile' => '9999999999',
        'address' => 'Test Address',
        'admission_date' => '2026-04-01',
        'status' => true,
    ]);
}

test('a parent can open attendance, fees and results pages', function () {
    $parent = User::factory()->create();
    $parent->assignRole('parent');

    createGuardedStudent($parent->id, 'A', 'Child One');

    $this->actingAs($parent)->get('/parent/attendance')->assertOk()->assertSee('Child One');
    $this->actingAs($parent)->get('/parent/fees')->assertOk()->assertSee('Child One');
    $this->actingAs($parent)->get('/parent/results')->assertOk();
});

test('a student cannot open parent portal pages', function () {
    $user = User::factory()->create();
    $user->assignRole('student');

    $this->actingAs($user)->get('/parent/attendance')->assertForbidden();
    $this->actingAs($user)->get('/parent/fees')->assertForbidden();
    $this->actingAs($user)->get('/parent/results')->assertForbidden();
});

test('parent fees page shows only their own childs payment history', function () {
    $parent = User::factory()->create();
    $parent->assignRole('parent');

    $myChild = createGuardedStudent($parent->id, 'B', 'My Child');

    $otherParent = User::factory()->create();
    $otherParent->assignRole('parent');
    $otherChild = createGuardedStudent($otherParent->id, 'C', 'Other Child');

    FeeType::create(['name' => 'Tuition Fee', 'code' => 'TUITION', 'status' => true]);

    FeeCollection::create([
        'student_registration_id' => $myChild->id,
        'fee_type_id' => FeeType::first()->id,
        'amount' => 5000,
        'discount' => 0,
        'fine' => 0,
        'paid_amount' => 5000,
        'balance' => 0,
        'payment_mode' => 'Cash',
        'receipt_no' => 'RCPT-MY-1',
        'payment_date' => '2026-06-01',
        'status' => true,
    ]);

    FeeCollection::create([
        'student_registration_id' => $otherChild->id,
        'fee_type_id' => FeeType::first()->id,
        'amount' => 7000,
        'discount' => 0,
        'fine' => 0,
        'paid_amount' => 7000,
        'balance' => 0,
        'payment_mode' => 'Cash',
        'receipt_no' => 'RCPT-OTHER-1',
        'payment_date' => '2026-06-01',
        'status' => true,
    ]);

    $response = $this->actingAs($parent)->get('/parent/fees');

    $response->assertOk()
        ->assertSee('RCPT-MY-1')
        ->assertDontSee('RCPT-OTHER-1')
        ->assertDontSee('Other Child');
});

test('a parent cannot view a child not linked to them by passing a foreign student_id', function () {
    $parent = User::factory()->create();
    $parent->assignRole('parent');
    $myChild = createGuardedStudent($parent->id, 'D', 'My Own Child');

    $otherParent = User::factory()->create();
    $otherParent->assignRole('parent');
    $otherChild = createGuardedStudent($otherParent->id, 'E', 'Foreign Child');

    Livewire::actingAs($parent)
        ->test(\App\Livewire\Portal\ParentPortal\Fees::class)
        ->set('student_id', $otherChild->id)
        ->call('$refresh')
        ->assertDontSee('Foreign Child')
        ->assertSee('My Own Child');
});

test('a parent with multiple children can switch between them and see the right results', function () {
    $parent = User::factory()->create();
    $parent->assignRole('parent');

    $childOne = createGuardedStudent($parent->id, 'F', 'First Child');
    $childTwo = createGuardedStudent($parent->id, 'G', 'Second Child');

    foreach ([['child' => $childOne, 'exam' => 'First Child Exam'], ['child' => $childTwo, 'exam' => 'Second Child Exam']] as $set) {
        $exam = \App\Models\Exam::create([
            'name' => $set['exam'],
            'course_id' => $set['child']->course_id,
            'batch_id' => $set['child']->batch_id,
            'status' => true,
        ]);

        $subject = Subject::create(['course_id' => $set['child']->course_id, 'name' => 'Subject', 'code' => 'SUB-' . $set['child']->id, 'status' => true]);

        $schedule = \App\Models\ExamSchedule::create([
            'exam_id' => $exam->id,
            'subject_id' => $subject->id,
            'exam_date' => '2026-08-01',
            'total_marks' => 100,
            'passing_marks' => 33,
        ]);

        \App\Models\ExamResult::create([
            'exam_schedule_id' => $schedule->id,
            'student_registration_id' => $set['child']->id,
            'marks_obtained' => 75,
            'is_absent' => false,
        ]);
    }

    Livewire::actingAs($parent)
        ->test(\App\Livewire\Portal\ParentPortal\Results::class)
        ->assertSee('First Child Exam')
        ->assertDontSee('Second Child Exam')
        ->set('student_id', $childTwo->id)
        ->assertSee('Second Child Exam')
        ->assertDontSee('First Child Exam');
});
