<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Resources\StudentResource;

class StudentController extends Controller
{
    



   public function store(StoreStudentRequest $request)
{



    $validated = $request->validated();


    $student = Student::create($validated);

    return response()->json([
        'message' => 'Student created successfully',
        'student' => $student
    ], 201);


}

public function show($id)
{
    $student = Student::with(['county', 'courses'])->find($id);

    if (!$student) {
        return response()->json(['message' => 'Student not found'], 404);
    }

    return new StudentResource($student);
}


public function index()
{
    $students = Student::with(['county', 'courses'])->get();

   
     
    return StudentResource::collection($students);
}





}


