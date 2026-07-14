<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Modules grouped by sidebar section. Every module gets
     * view / create / edit / delete permissions.
     */
    protected array $modules = [
        'User Management' => ['users', 'roles', 'permissions'],
        'Master Data' => ['academic-years', 'academic-sessions', 'courses', 'subjects', 'batches', 'classrooms', 'shifts'],
        'Timetable' => ['timetables'],
        'Academics' => ['study-materials', 'homework'],
        'Student Management' => ['students', 'enquiries'],
        'Teacher Management' => ['teachers'],
        'Fee Management' => ['fee-types', 'fee-structures', 'fee-collections'],
        'Expense Management' => ['expense-categories', 'expenses', 'salary-payments'],
        'Attendance' => ['student-attendance', 'teacher-attendance'],
        'Examination' => ['exams', 'exam-results'],
        'Reports' => ['reports'],
        'Website' => ['gallery', 'testimonials', 'notices'],
        'Settings' => ['settings', 'activity-logs'],
    ];

    protected array $actions = ['view', 'create', 'edit', 'delete'];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($this->modules as $group => $modules) {
            foreach ($modules as $module) {
                foreach ($this->actions as $action) {
                    Permission::firstOrCreate(
                        ['name' => "{$module}.{$action}", 'guard_name' => 'web'],
                        ['module' => $group],
                    );
                }
            }
        }

        // super-admin bypasses all checks via Gate::before (see AppServiceProvider)
        Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $accountant = Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web']);
        $accountant->syncPermissions([
            'students.view',
            'fee-types.view', 'fee-types.create', 'fee-types.edit',
            'fee-structures.view', 'fee-structures.create', 'fee-structures.edit',
            'fee-collections.view', 'fee-collections.create', 'fee-collections.edit',
            'expense-categories.view', 'expense-categories.create', 'expense-categories.edit',
            'expenses.view', 'expenses.create', 'expenses.edit',
            'salary-payments.view', 'salary-payments.create', 'salary-payments.edit',
            'reports.view',
        ]);

        $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $teacher->syncPermissions([
            'students.view',
            'student-attendance.view', 'student-attendance.create', 'student-attendance.edit',
            'exams.view',
            'exam-results.view', 'exam-results.create', 'exam-results.edit',
            'study-materials.view', 'study-materials.create', 'study-materials.edit', 'study-materials.delete',
            'homework.view', 'homework.create', 'homework.edit', 'homework.delete',
        ]);

        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'parent', 'guard_name' => 'web']);

        // Dedicated super admin account for first login.
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@coaching.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'status' => true,
            ],
        );
        $superAdmin->syncRoles(['super-admin']);

        // Existing users lost their legacy role_id; keep the earliest
        // account usable by promoting it to super-admin.
        $firstUser = User::orderBy('id')->first();
        if ($firstUser && $firstUser->roles->isEmpty()) {
            $firstUser->assignRole('super-admin');
        }
    }
}
