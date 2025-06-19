<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCourseRequest;

use App\Models\Course; 

class CourseController extends Controller
{
    //

    public function store(StoreCourseRequest $request){

        $validated = $request->validated();

        
        $course = Course::create($validated);

        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course
        ], 201);


      
    }
}

