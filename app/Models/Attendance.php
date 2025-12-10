<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $table = 'attendance';

    protected $fillable = [
        'id',
        'class_name',
        'teacher_id',
        'attendance_date',
        'total_students',
        'present',
        'absent',
        'late',
        'status',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    /**
     * Get the teacher that owns the attendance record.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}

