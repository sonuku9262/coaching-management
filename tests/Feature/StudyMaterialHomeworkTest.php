<?php

use App\Models\AcademicSession;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Shift;
use App\Models\StudentRegistration;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherBatchSubject;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    Storage::fake('public');
});

function createAcademicsFixtures(string $tag = 'AC'): array
{
    $year = AcademicYear::create(['name' => 'AC-Year-' . $tag, 'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'status' => true]);
    $session = AcademicSession::create(['academic_year_id' => $year->id, 'name' => 'AC-Session-' . $tag, 'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'status' => true]);
    $course = Course::create(['name' => 'AC Course ' . $tag, 'code' => 'AC-' . $tag, 'duration' => 1, 'fees' => 10000, 'status' => true]);
    $batch = Batch::create(['academic_year_id' => $year->id, 'academic_session_id' => $session->id, 'course_id' => $course->id, 'name' => 'AC Batch ' . $tag, 'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'capacity' => 30, 'status' => true]);
    $classroom = Classroom::create(['name' => 'AC Room ' . $tag, 'room_no' => 'AC-' . $tag, 'capacity' => 30, 'status' => true]);
    $shift = Shift::create(['name' => 'AC Shift ' . $tag, 'start_time' => '08:00', 'end_time' => '12:00', 'status' => true]);
    $subject = Subject::create(['course_id' => $course->id, 'name' => 'AC Subject ' . $tag, 'code' => 'ACS-' . $tag, 'status' => true]);

    $student = StudentRegistration::create([
        'admission_no' => 'ADM-AC-' . $tag,
        'academic_year_id' => $year->id,
        'academic_session_id' => $session->id,
        'course_id' => $course->id,
        'batch_id' => $batch->id,
        'classroom_id' => $classroom->id,
        'shift_id' => $shift->id,
        'name' => 'AC Student ' . $tag,
        'father_name' => 'Father',
        'gender' => 'Male',
        'dob' => '2010-01-01',
        'mobile' => '9999999999',
        'address' => 'Address',
        'admission_date' => '2026-04-01',
        'status' => true,
    ]);

    return compact('year', 'session', 'course', 'batch', 'classroom', 'shift', 'subject', 'student');
}

function createAssignedAcademicsTeacher(Batch $batch, Subject $subject): Teacher
{
    $user = User::factory()->create();
    $user->assignRole('teacher');

    $teacher = Teacher::create([
        'user_id' => $user->id,
        'employee_id' => 'EMP-AC-' . $user->id,
        'name' => 'Academics Teacher',
        'mobile' => '7777777777',
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

test('an admin can view study material and homework pages but a student cannot', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)->get('/admin/study-material')->assertOk();
    $this->actingAs($admin)->get('/admin/homework')->assertOk();

    $student = User::factory()->create();
    $student->assignRole('student');

    $this->actingAs($student)->get('/admin/study-material')->assertForbidden();
    $this->actingAs($student)->get('/admin/homework')->assertForbidden();
});

test('a teacher can upload study material for their assigned batch and subject', function () {
    $fixtures = createAcademicsFixtures();
    $teacher = createAssignedAcademicsTeacher($fixtures['batch'], $fixtures['subject']);

    Livewire::actingAs($teacher->user)
        ->test(\App\Livewire\Portal\Teacher\StudyMaterial::class)
        ->set('batch_id', $fixtures['batch']->id)
        ->set('subject_id', $fixtures['subject']->id)
        ->set('title', 'Chapter 1 Notes')
        ->set('file', UploadedFile::fake()->create('notes.pdf', 100))
        ->call('save');

    $this->assertDatabaseHas('study_materials', [
        'batch_id' => $fixtures['batch']->id,
        'subject_id' => $fixtures['subject']->id,
        'teacher_id' => $teacher->id,
        'title' => 'Chapter 1 Notes',
    ]);
});

test('a teacher cannot upload study material for a batch they are not assigned to', function () {
    $fixtures = createAcademicsFixtures('UN1');
    $otherFixtures = createAcademicsFixtures('UN2');
    $teacher = createAssignedAcademicsTeacher($fixtures['batch'], $fixtures['subject']);

    Livewire::actingAs($teacher->user)
        ->test(\App\Livewire\Portal\Teacher\StudyMaterial::class)
        ->set('batch_id', $otherFixtures['batch']->id)
        ->set('subject_id', $otherFixtures['subject']->id)
        ->set('title', 'Should Not Save')
        ->call('save')
        ->assertForbidden();

    $this->assertDatabaseMissing('study_materials', ['title' => 'Should Not Save']);
});

test('a teacher cannot see, edit or delete another subjects material within a shared batch', function () {
    $fixtures = createAcademicsFixtures('SHARED');
    $teacher = createAssignedAcademicsTeacher($fixtures['batch'], $fixtures['subject']);

    $otherSubject = Subject::create(['course_id' => $fixtures['course']->id, 'name' => 'Other Subject', 'code' => 'OTH-SHARED', 'status' => true]);

    $otherMaterial = \App\Models\StudyMaterial::create([
        'batch_id' => $fixtures['batch']->id,
        'subject_id' => $otherSubject->id,
        'title' => 'Other Teacher Notes',
        'status' => true,
    ]);

    Livewire::actingAs($teacher->user)
        ->test(\App\Livewire\Portal\Teacher\StudyMaterial::class)
        ->assertDontSee('Other Teacher Notes');

    Livewire::actingAs($teacher->user)
        ->test(\App\Livewire\Portal\Teacher\StudyMaterial::class)
        ->call('edit', $otherMaterial->id)
        ->assertForbidden();

    Livewire::actingAs($teacher->user)
        ->test(\App\Livewire\Portal\Teacher\StudyMaterial::class)
        ->call('delete', $otherMaterial->id)
        ->assertForbidden();

    $this->assertDatabaseHas('study_materials', ['id' => $otherMaterial->id]);
});

test('a teacher can assign homework for their assigned batch and subject', function () {
    $fixtures = createAcademicsFixtures('HW');
    $teacher = createAssignedAcademicsTeacher($fixtures['batch'], $fixtures['subject']);

    Livewire::actingAs($teacher->user)
        ->test(\App\Livewire\Portal\Teacher\Homework::class)
        ->set('batch_id', $fixtures['batch']->id)
        ->set('subject_id', $fixtures['subject']->id)
        ->set('title', 'Solve Exercise 5')
        ->set('due_date', now()->addDays(3)->format('Y-m-d'))
        ->call('save');

    $this->assertDatabaseHas('homework', [
        'batch_id' => $fixtures['batch']->id,
        'teacher_id' => $teacher->id,
        'title' => 'Solve Exercise 5',
    ]);
});

test('a student only sees study material and homework for their own batch', function () {
    $fixtures = createAcademicsFixtures('SM1');
    $otherFixtures = createAcademicsFixtures('SM2');

    $user = User::factory()->create();
    $user->assignRole('student');
    $fixtures['student']->update(['user_id' => $user->id]);

    \App\Models\StudyMaterial::create([
        'batch_id' => $fixtures['batch']->id,
        'subject_id' => $fixtures['subject']->id,
        'title' => 'My Batch Notes',
        'status' => true,
    ]);

    \App\Models\StudyMaterial::create([
        'batch_id' => $otherFixtures['batch']->id,
        'subject_id' => $otherFixtures['subject']->id,
        'title' => 'Other Batch Notes',
        'status' => true,
    ]);

    \App\Models\Homework::create([
        'batch_id' => $fixtures['batch']->id,
        'subject_id' => $fixtures['subject']->id,
        'title' => 'My Batch Homework',
        'due_date' => now()->addDays(2)->format('Y-m-d'),
        'status' => true,
    ]);

    \App\Models\Homework::create([
        'batch_id' => $otherFixtures['batch']->id,
        'subject_id' => $otherFixtures['subject']->id,
        'title' => 'Other Batch Homework',
        'due_date' => now()->addDays(2)->format('Y-m-d'),
        'status' => true,
    ]);

    $materialResponse = $this->actingAs($user)->get('/student/study-material');
    $materialResponse->assertOk()->assertSee('My Batch Notes')->assertDontSee('Other Batch Notes');

    $homeworkResponse = $this->actingAs($user)->get('/student/homework');
    $homeworkResponse->assertOk()->assertSee('My Batch Homework')->assertDontSee('Other Batch Homework');
});
