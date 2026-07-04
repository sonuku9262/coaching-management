<?php

namespace App\Services;

use App\Models\StudentRegistration;
use App\Models\Teacher;
use App\Models\User;
use App\Notifications\PortalAccountCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PortalAccountService
{
    /**
     * Create a login account for a teacher and link it.
     * Default password is the teacher's mobile number.
     */
    public function createTeacherLogin(Teacher $teacher): User
    {
        if ($teacher->user_id) {
            throw ValidationException::withMessages(['account' => 'Teacher already has a login account.']);
        }

        $this->assertEmailAvailable($teacher->email, 'Teacher');

        return DB::transaction(function () use ($teacher) {
            $user = User::create([
                'name' => $teacher->name,
                'email' => $teacher->email,
                'password' => Hash::make($this->defaultPassword($teacher->mobile)),
                'status' => true,
            ]);

            $user->assignRole('teacher');
            $teacher->update(['user_id' => $user->id]);

            $user->notify(new PortalAccountCreated('teacher'));

            return $user;
        });
    }

    /**
     * Create a login account for a student and link it.
     * Default password is the student's mobile number.
     */
    public function createStudentLogin(StudentRegistration $student): User
    {
        if ($student->user_id) {
            throw ValidationException::withMessages(['account' => 'Student already has a login account.']);
        }

        $this->assertEmailAvailable($student->email, 'Student');

        return DB::transaction(function () use ($student) {
            $user = User::create([
                'name' => $student->name,
                'email' => $student->email,
                'password' => Hash::make($this->defaultPassword($student->mobile)),
                'status' => true,
            ]);

            $user->assignRole('student');
            $student->update(['user_id' => $user->id]);

            $user->notify(new PortalAccountCreated('student'));

            return $user;
        });
    }

    /**
     * Create (or reuse) a guardian login for a student's parent and link it.
     * A parent with children in multiple courses keeps a single account.
     */
    public function createGuardianLogin(StudentRegistration $student): User
    {
        if ($student->guardian_user_id) {
            throw ValidationException::withMessages(['account' => 'Guardian already has a login account.']);
        }

        if (! $student->guardian_email) {
            throw ValidationException::withMessages(['account' => 'Add a guardian email to the registration first.']);
        }

        return DB::transaction(function () use ($student) {
            $user = User::where('email', $student->guardian_email)->first();

            if ($user) {
                if (! $user->hasRole('parent')) {
                    throw ValidationException::withMessages(['account' => 'Guardian email belongs to a non-parent account.']);
                }
            } else {
                $user = User::create([
                    'name' => $student->father_name ?: 'Guardian of ' . $student->name,
                    'email' => $student->guardian_email,
                    'password' => Hash::make($this->defaultPassword($student->mobile)),
                    'status' => true,
                ]);

                $user->assignRole('parent');

                $user->notify(new PortalAccountCreated('parent'));
            }

            $student->update(['guardian_user_id' => $user->id]);

            return $user;
        });
    }

    protected function assertEmailAvailable(?string $email, string $who): void
    {
        if (! $email) {
            throw ValidationException::withMessages(['account' => "{$who} has no email address; add one first."]);
        }

        if (User::where('email', $email)->exists()) {
            throw ValidationException::withMessages(['account' => "A user with email {$email} already exists."]);
        }
    }

    protected function defaultPassword(?string $mobile): string
    {
        return $mobile ?: 'password';
    }
}
