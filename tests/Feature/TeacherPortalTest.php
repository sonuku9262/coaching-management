<?php

use App\Models\AcademicSession;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamSchedule;
use App\Models\Shift;
use App\Models\StudentAttendance;
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

/**
 * Builds a course/batch/subject/student chain so teacher-portal tests
 * have real assignable data to work with.
 */
function createTeacherPortalFixtures(string $courseCode = 'SCI'): array
{
    $year = AcademicYear::create([
        'name' => '2026-27',
        'start_date' => '2026-04-01',
        'end_date' => '2027-03-31',
        'status' => true,
    ]);

    $session = AcademicSession::create([
        'academic_year_id' => $year->id,
        'name' => 'Session 1',
        'start_date' => '2026-04-01',
        'end_date' => '2027-03-31',
        'status' => true,
    ]);

    $course = Course::create([
        'name' => 'Science',
        'code' => $courseCode,
        'duration' => 1,
        'fees' => 10000,
        'status' => true,
    ]);

    $batch = Batch::create([
        'academic_year_id' => $year->id,
        'academic_session_id' => $session->id,
        'course_id' => $course->id,
        'name' => 'Batch A',
        'start_date' => '2026-04-01',
        'end_date' => '2027-03-31',
        'capacity' => 30,
        'status' => true,
    ]);

    $classroom = Classroom::create(['name' => 'Room 1', 'room_no' => 'R-' . $courseCode, 'capacity' => 30, 'status' => true]);

    $shift = Shift::create(['name' => 'Morning', 'start_time' => '08:00', 'end_time' => '12:00', 'status' => true]);

    $subject = Subject::create(['course_id' => $course->id, 'name' => 'Physics', 'code' => 'PHY-' . $courseCode, 'status' => true]);

    $student = StudentRegistration::create([
        'admission_no' => 'ADM-' . $courseCode . '-1',
        'academic_year_id' => $year->id,
        'academic_session_id' => $session->id,
        'course_id' => $course->id,
        'batch_id' => $batch->id,
        'classroom_id' => $classroom->id,
        'shift_id' => $shift->id,
        'name' => 'Test Student',
        'father_name' => 'Test Father',
        'gender' => 'Male',
        'dob' => '2010-01-01',
        'mobile' => '9999999999',
        'address' => 'Test Address',
        'admission_date' => '2026-04-01',
        'status' => true,
    ]);

    return compact('year', 'session', 'course', 'batch', 'classroom', 'shift', 'subject', 'student');
}

function createAssignedTeacher(Batch $batch, Subject $subject): Teacher
{
    $user = User::factory()->create();
    $user->assignRole('teacher');

    $teacher = Teacher::create([
        'user_id' => $user->id,
        'employee_id' => 'EMP-' . $user->id,
        'name' => 'Test Teacher',
        'mobile' => '8888888888',
        'joining_date' => '2026-01-01',
        'status' => true,
    ]);

    TeacherBatchSubject::create([
        'teacher_id' => $teacher->id,
        'batch_id' => $batch->id,
        'subject_id' => $subject->id,
    ]);

    return $teacher;
}

test('a teacher can open my-batches, attendance and marks pages', function () {
    $fixtures = createTeacherPortalFixtures();
    $teacher = createAssignedTeacher($fixtures['batch'], $fixtures['subject']);

    $this->actingAs($teacher->user)->get('/teacher/batches')->assertOk()->assertSee('Batch A');
    $this->actingAs($teacher->user)->get('/teacher/attendance')->assertOk();
    $this->actingAs($teacher->user)->get('/teacher/marks')->assertOk();
});

test('a student cannot open teacher portal pages', function () {
    $user = User::factory()->create();
    $user->assignRole('student');

    $this->actingAs($user)->get('/teacher/batches')->assertForbidden();
    $this->actingAs($user)->get('/teacher/attendance')->assertForbidden();
    $this->actingAs($user)->get('/teacher/marks')->assertForbidden();
});

test('a teacher can mark attendance for their assigned batch', function () {
    $fixtures = createTeacherPortalFixtures();
    $teacher = createAssignedTeacher($fixtures['batch'], $fixtures['subject']);

    Livewire::actingAs($teacher->user)
        ->test(\App\Livewire\Portal\Teacher\Attendance::class)
        ->set('batch_id', $fixtures['batch']->id)
        ->call('loadStudents')
        ->set('students.0.status', 'Absent')
        ->call('save');

    $this->assertDatabaseHas('student_attendances', [
        'student_registration_id' => $fixtures['student']->id,
        'batch_id' => $fixtures['batch']->id,
        'status' => 'Absent',
    ]);
});

test('a teacher cannot mark attendance for a batch they are not assigned to', function () {
    $fixtures = createTeacherPortalFixtures('SCI2');
    $otherBatchFixtures = createTeacherPortalFixtures('SCI3');
    $teacher = createAssignedTeacher($fixtures['batch'], $fixtures['subject']);

    Livewire::actingAs($teacher->user)
        ->test(\App\Livewire\Portal\Teacher\Attendance::class)
        ->set('batch_id', $otherBatchFixtures['batch']->id)
        ->set('students', [[
            'student_id' => $otherBatchFixtures['student']->id,
            'name' => $otherBatchFixtures['student']->name,
            'status' => 'Present',
        ]])
        ->call('save')
        ->assertForbidden();

    $this->assertDatabaseMissing('student_attendances', [
        'student_registration_id' => $otherBatchFixtures['student']->id,
    ]);
});

test('a teacher can enter exam marks for their assigned batch and subject', function () {
    $fixtures = createTeacherPortalFixtures();
    $teacher = createAssignedTeacher($fixtures['batch'], $fixtures['subject']);

    $exam = Exam::create([
        'name' => 'Mid Term',
        'course_id' => $fixtures['course']->id,
        'batch_id' => $fixtures['batch']->id,
        'status' => true,
    ]);

    $schedule = ExamSchedule::create([
        'exam_id' => $exam->id,
        'subject_id' => $fixtures['subject']->id,
        'exam_date' => '2026-08-01',
        'total_marks' => 100,
        'passing_marks' => 33,
    ]);

    Livewire::actingAs($teacher->user)
        ->test(\App\Livewire\Portal\Teacher\Marks::class)
        ->set('exam_id', $exam->id)
        ->call('loadRows')
        ->set('schedule_id', $schedule->id)
        ->call('loadRows')
        ->set('rows.0.marks', 88)
        ->call('save');

    $this->assertDatabaseHas('exam_results', [
        'exam_schedule_id' => $schedule->id,
        'student_registration_id' => $fixtures['student']->id,
        'marks_obtained' => 88,
    ]);
});

test('a teacher cannot enter marks for a subject not assigned to them', function () {
    $fixtures = createTeacherPortalFixtures('SCI4');
    $otherSubject = Subject::create(['course_id' => $fixtures['course']->id, 'name' => 'Chemistry', 'code' => 'CHE-SCI4', 'status' => true]);
    $teacher = createAssignedTeacher($fixtures['batch'], $fixtures['subject']);

    $exam = Exam::create([
        'name' => 'Mid Term',
        'course_id' => $fixtures['course']->id,
        'batch_id' => $fixtures['batch']->id,
        'status' => true,
    ]);

    $schedule = ExamSchedule::create([
        'exam_id' => $exam->id,
        'subject_id' => $otherSubject->id,
        'exam_date' => '2026-08-01',
        'total_marks' => 100,
        'passing_marks' => 33,
    ]);

    Livewire::actingAs($teacher->user)
        ->test(\App\Livewire\Portal\Teacher\Marks::class)
        ->set('schedule_id', $schedule->id)
        ->set('rows', [[
            'student_id' => $fixtures['student']->id,
            'admission_no' => $fixtures['student']->admission_no,
            'name' => $fixtures['student']->name,
            'marks' => 50,
            'is_absent' => false,
        ]])
        ->call('save')
        ->assertForbidden();

    $this->assertDatabaseMissing('exam_results', [
        'exam_schedule_id' => $schedule->id,
    ]);
});
