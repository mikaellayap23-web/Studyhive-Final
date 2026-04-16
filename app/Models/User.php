<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'status',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        ];
    }

    /**
     * Get the read announcements for the user.
     */
    public function announcementReads()
    {
        return $this->hasMany(AnnouncementRead::class);
    }

    /**
     * Get the modules assigned to the teacher.
     */
    public function assignedModules()
    {
        return $this->hasMany(Module::class, 'assigned_teacher_id');
    }

    /**
     * Get the enrollments for the student.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the modules the user is enrolled in.
     */
    public function enrolledModules()
    {
        return $this->hasManyThrough(Module::class, Enrollment::class, 'user_id', 'id', 'id', 'module_id');
    }

    /**
     * Get module progress records for the user.
     */
    public function moduleProgress()
    {
        return $this->hasMany(ModuleProgress::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Check if the user is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the user is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
