<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $table = 'student';
    protected $fillable = ['name', 'email', 'country_id'];



    function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student', 'student_id', 'course_id');
    }


    function county()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }












}
