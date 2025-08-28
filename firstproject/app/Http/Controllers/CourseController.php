<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\updatecourse;
use App\Http\Resources\CourseResourse;
use App\Models\Course; 
use Illuminate\Support\Facades\Log;





class CourseController extends Controller
{

public function addCourse(StoreCourseRequest $request)
{
    try {
        $course = new Course();
        $course->fill($request->all());
        $course->save();

        return response()->json([
            'message' => 'Course created successfully',
            'course' => new CourseResourse($course),
        ], 200);
    } catch (\Exception $e) {
       
        Log::error('Failed to add course: ' . $e->getMessage(), [
            'exception' => $e,
            'request_data' => $request->all(),
        ]);

        return response()->json([
            'message' => 'Failed to create course',
            'error' => $e->getMessage()
        ], 500);
    }
}
   

    public function store(StoreCourseRequest $request){

        $course = new Course();
        $course->fill($request->all());
        $course->save();

        return response()->json([
            'message' => 'Course created successfully',
            'course' => new CourseResourse($course),
        ], 200);


      
    }
//Note: edit by id  in this function 

    public function update(updatecourse $request,$id){

        $course = Course::findOrFail($id);
   
        $course->fill($request->all());
        $course->save();

        return response()->json([
            'message' => 'Course updated successfully',
            'course' => new CourseResourse($course),
        ], 200);

    }

    public function getallcourses(){

        $courses = Course::all();

        return response()->json([
            'message' => 'Courses retrieved successfully',
            'courses' => CourseResourse::collection($courses),
        ], 200);
    }








}

