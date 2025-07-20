<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\CourseStatus;

class Course extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'course';
    protected $fillable = ['title','status'];
    
   protected $casts = [
        'status' => CourseStatus::class, 
    ];


    public function students()
{
   return $this->belongsToMany(Student::class, 'course_student', 'course_id', 'student_id');
}





}
