<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'title',
        'course_id',
        'grade',
        'exam_date',
        'exam_time',
        'duration',
        'type',
        'status',
        'max_score',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'exam_time' => 'datetime',
    ];

    /**
     * Get the course that owns the exam.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the exam results for the exam.
     */
    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }
}

