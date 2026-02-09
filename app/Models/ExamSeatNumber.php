<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSeatNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'class_section_id',
        'student_id',
        'seat_number',
        'roll_number'
    ];

    public function exam() {
        return $this->belongsTo(Exam::class);
    }

    public function class_section() {
        return $this->belongsTo(ClassSection::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
