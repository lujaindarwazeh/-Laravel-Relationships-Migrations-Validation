<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\updatecourse;
use App\Http\Resources\CourseResourse;
use App\Models\Course; 





class CourseController extends Controller
{
   

    public function store(StoreCourseRequest $request){

        $course = new Course();
        $course->fill($request->all());
        $course->save();

        return response()->json([
            'message' => 'Course created successfully',
            'course' => new CourseResourse($course),
        ], 200);


      
    }

    public function update(updatecourse $request,course $course){

        $course->fill($request->all());
        $course->save();

        return response()->json([
            'message' => 'Course updated successfully',
            'course' => new CourseResourse($course),
        ], 200);

    }








}

