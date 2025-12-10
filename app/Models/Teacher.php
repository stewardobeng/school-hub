<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'subject',
        'status',
        'join_date',
        'date_of_birth',
        'address',
        'education',
        'experience',
        'avatar_url',
    ];

    protected $casts = [
        'join_date' => 'date',
        'date_of_birth' => 'date',
    ];

    /**
     * Get the courses for the teacher.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get the attendance records for the teacher.
     */
    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}

