<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, LogsActivity, Notifiable;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logExcept(['password'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }

    /**
     * The route this user should land on after login, by role priority.
     * Multi-role users go to the highest-privilege dashboard; users with
     * no role yet are sent to the public site.
     */
    public function dashboardRoute(): string
    {
        if ($this->hasAnyRole(['super-admin', 'admin', 'accountant'])) {
            return 'dashboard';
        }

        if ($this->hasRole('teacher')) {
            return 'teacher.dashboard';
        }

        if ($this->hasRole('student')) {
            return 'student.dashboard';
        }

        if ($this->hasRole('parent')) {
            return 'parent.dashboard';
        }

        // custom roles created by the admin use the admin dashboard;
        // users with no role are parked on the public site
        return $this->roles->isNotEmpty() ? 'dashboard' : 'home';
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function studentRegistration()
    {
        return $this->hasOne(StudentRegistration::class);
    }
}
