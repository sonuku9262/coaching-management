<?php

use App\Models\AcademicSession;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\FeeCollection;
use App\Models\FeeType;
use App\Models\Shift;
use App\Models\StudentRegistration;
use App\Models\Teacher;
use App\Models\TeacherSalaryPayment;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('an admin can view expense pages but a student cannot', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)->get('/admin/expense-categories')->assertOk();
    $this->actingAs($admin)->get('/admin/expenses')->assertOk();
    $this->actingAs($admin)->get('/admin/salary-payments')->assertOk();
    $this->actingAs($admin)->get('/admin/reports/profit-loss')->assertOk();

    $student = User::factory()->create();
    $student->assignRole('student');

    $this->actingAs($student)->get('/admin/expenses')->assertForbidden();
    $this->actingAs($student)->get('/admin/salary-payments')->assertForbidden();
});

test('an accountant can manage expenses and salary payments', function () {
    $user = User::factory()->create();
    $user->assignRole('accountant');

    $this->actingAs($user)->get('/admin/expense-categories')->assertOk();
    $this->actingAs($user)->get('/admin/expenses')->assertOk();
    $this->actingAs($user)->get('/admin/salary-payments')->assertOk();
});

test('an admin can create an expense category and an expense', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\ExpenseManagement\ExpenseCategory\Index::class)
        ->set('name', 'Electricity')
        ->set('code', 'ELEC')
        ->call('save');

    $this->assertDatabaseHas('expense_categories', ['name' => 'Electricity', 'code' => 'ELEC']);

    $category = ExpenseCategory::where('code', 'ELEC')->first();

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\ExpenseManagement\Expense\Index::class)
        ->set('expense_category_id', $category->id)
        ->set('amount', 2500)
        ->set('expense_date', now()->format('Y-m-d'))
        ->set('paid_to', 'State Electricity Board')
        ->call('save');

    $this->assertDatabaseHas('expenses', [
        'expense_category_id' => $category->id,
        'amount' => 2500,
        'paid_to' => 'State Electricity Board',
    ]);
});

test('an admin can record a teacher salary payment with amount auto-filled', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $teacher = Teacher::create([
        'employee_id' => 'EMP-SAL-1',
        'name' => 'Salary Test Teacher',
        'mobile' => '9998887770',
        'joining_date' => '2026-01-01',
        'salary' => 32000,
        'status' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\ExpenseManagement\SalaryPayment\Index::class)
        ->set('teacher_id', $teacher->id)
        ->assertSet('amount', 32000)
        ->assertSet('paid_amount', 32000)
        ->set('salary_month', '2026-07')
        ->call('save');

    $this->assertDatabaseHas('teacher_salary_payments', [
        'teacher_id' => $teacher->id,
        'amount' => 32000,
        'paid_amount' => 32000,
    ]);
});

test('a duplicate salary payment for the same teacher and month is rejected', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $teacher = Teacher::create([
        'employee_id' => 'EMP-SAL-2',
        'name' => 'Duplicate Test Teacher',
        'mobile' => '9998887771',
        'joining_date' => '2026-01-01',
        'salary' => 25000,
        'status' => true,
    ]);

    TeacherSalaryPayment::create([
        'teacher_id' => $teacher->id,
        'salary_month' => '2026-07-01',
        'amount' => 25000,
        'deduction' => 0,
        'paid_amount' => 25000,
        'payment_date' => '2026-07-05',
        'payment_mode' => 'Cash',
        'voucher_no' => 'SAL-2026-00001',
    ]);

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\ExpenseManagement\SalaryPayment\Index::class)
        ->set('teacher_id', $teacher->id)
        ->set('salary_month', '2026-07')
        ->set('amount', 25000)
        ->call('save')
        ->assertHasErrors(['salary_month']);

    expect(TeacherSalaryPayment::where('teacher_id', $teacher->id)->count())->toBe(1);
});

test('the profit and loss report totals income against expenses and salary', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // income: one fee collection
    $year = AcademicYear::create(['name' => 'PL-Year', 'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'status' => true]);
    $session = AcademicSession::create(['academic_year_id' => $year->id, 'name' => 'PL-Session', 'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'status' => true]);
    $course = Course::create(['name' => 'PL Course', 'code' => 'PLC', 'duration' => 1, 'fees' => 10000, 'status' => true]);
    $batch = Batch::create(['academic_year_id' => $year->id, 'academic_session_id' => $session->id, 'course_id' => $course->id, 'name' => 'PL Batch', 'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'capacity' => 30, 'status' => true]);
    $classroom = Classroom::create(['name' => 'PL Room', 'room_no' => 'PL-1', 'capacity' => 30, 'status' => true]);
    $shift = Shift::create(['name' => 'PL Shift', 'start_time' => '08:00', 'end_time' => '12:00', 'status' => true]);

    $student = StudentRegistration::create([
        'admission_no' => 'ADM-PL-1',
        'academic_year_id' => $year->id,
        'academic_session_id' => $session->id,
        'course_id' => $course->id,
        'batch_id' => $batch->id,
        'classroom_id' => $classroom->id,
        'shift_id' => $shift->id,
        'name' => 'PL Student',
        'father_name' => 'Father',
        'gender' => 'Male',
        'dob' => '2010-01-01',
        'mobile' => '9999999999',
        'address' => 'Address',
        'admission_date' => '2026-04-01',
        'status' => true,
    ]);

    $feeType = FeeType::create(['name' => 'PL Tuition', 'code' => 'PLTUT', 'status' => true]);

    FeeCollection::create([
        'student_registration_id' => $student->id,
        'fee_type_id' => $feeType->id,
        'amount' => 10000,
        'discount' => 0,
        'fine' => 0,
        'paid_amount' => 10000,
        'balance' => 0,
        'payment_mode' => 'Cash',
        'receipt_no' => 'RCPT-PL-1',
        'payment_date' => now()->format('Y-m-d'),
        'status' => true,
    ]);

    // expense
    $category = ExpenseCategory::create(['name' => 'PL Rent', 'code' => 'PLRENT', 'status' => true]);

    Expense::create([
        'expense_category_id' => $category->id,
        'amount' => 3000,
        'expense_date' => now()->format('Y-m-d'),
        'payment_mode' => 'Cash',
        'voucher_no' => 'EXP-PL-1',
        'status' => true,
    ]);

    // salary
    $teacher = Teacher::create([
        'employee_id' => 'EMP-PL-1',
        'name' => 'PL Teacher',
        'mobile' => '9998887772',
        'joining_date' => '2026-01-01',
        'salary' => 4000,
        'status' => true,
    ]);

    TeacherSalaryPayment::create([
        'teacher_id' => $teacher->id,
        'salary_month' => now()->startOfMonth()->format('Y-m-d'),
        'amount' => 4000,
        'deduction' => 0,
        'paid_amount' => 4000,
        'payment_date' => now()->format('Y-m-d'),
        'payment_mode' => 'Cash',
        'voucher_no' => 'SAL-PL-1',
    ]);

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\Reports\ProfitLossReport::class)
        ->set('from_date', now()->startOfMonth()->format('Y-m-d'))
        ->set('to_date', now()->format('Y-m-d'))
        ->assertSee('₹ 10,000.00') // total income
        ->assertSee('₹ 7,000.00') // total expense (3,000 rent + 4,000 salary)
        ->assertSee('Net Profit')
        ->assertSee('₹ 3,000.00'); // net profit
});
