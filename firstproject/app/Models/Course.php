<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'course';
    protected $fillable = ['title'];


    public function students()
{
   return $this->belongsToMany(Student::class, 'course_student', 'course_id', 'student_id');
}





}
