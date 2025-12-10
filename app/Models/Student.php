<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
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
        'grade',
        'status',
        'enrollment_date',
        'date_of_birth',
        'address',
        'parent_name',
        'parent_email',
        'parent_phone',
        'avatar_url',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'date_of_birth' => 'date',
    ];

    /**
     * Get the courses for the student.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_enrollments', 'student_id', 'course_id')
            ->withPivot('enrollment_date', 'status')
            ->withTimestamps();
    }

    /**
     * Get the payments for the student.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the exam results for the student.
     */
    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}

