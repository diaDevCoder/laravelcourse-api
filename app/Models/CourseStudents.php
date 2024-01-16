<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseStudents extends Model
{
    use HasFactory;

    public function courses() {
        return $this->belongsToMany(Student::class, 'student_id');
    }
}
