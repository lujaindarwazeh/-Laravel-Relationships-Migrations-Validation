<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;
use App\Http\Requests\updatestudent;
use App\Mail\LateEnterMail;
use Illuminate\Support\Facades\Mail;

class StudentController extends Controller
{
    
   public function store(StoreStudentRequest $request)
{

     $student = new Student();
     $student->fill($request->all());
     $student->save();




    return response()->json([
        'success' => true,
        'message' => 'Student created successfully',
        'student' => new StudentResource($student),
    ], 200);


}

public function show($id)
{
    
    $student = Student::findOrFail($id);

    if (!$student) {
        return response()->json([
            'success' => false,
            'message' => 'Student not found',
        ], 404);
    }
    return response()->json([
        'success' => true,
        'student' => new StudentResource($student),
        'message' => 'Student retrieved successfully',
    ], 200);
}


public function index(Request $request)
{
   $query = Student::query();


    $query->orderBy('created_at', 'desc');

    $request->validate([
    'search' => 'nullable|string|max:255',
]);

     if ($request->has('search')) {
        $search = $request->input('search');

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });

    }


    $students = $query->get();

    
    return response()->json([
        'success' => true,
        'students' => StudentResource::collection($students),
        'message' => 'Students retrieved successfully',
    ], 200);
}



public function destroy($id)
{
    $student = Student::findOrFail($id);

    if (!$student) {
        return response()->json([
            'success' => false,
            'message' => 'Student not found',
        ], 404);
    }
    

    $student->delete();

    return response()->json([
        'success' => true,
        'message' => 'Student deleted successfully',
    ], 200);
}


public function bulkDelete(Request $request)
{
    
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'integer|exists:student,id',
    ]);

    $ids = $request->input('ids');

   
    Student::whereIn('id', $ids)->delete();

    return response()->json([
        'success' => true,
        'message' => 'Students deleted successfully',
        'deleted_ids' => $ids
    ], 200);



}


public function update($id, updatestudent $request)
{
    $student = Student::findOrFail($id);
    
    $student->fill($request->all());
    $student->save();

    return response()->json([
        'success' => true,
        'message' => 'Student updated successfully',
        'student' => new StudentResource($student),
    ], 200);

}




public function sendLateEntryNotification($id)
{
    $student = Student::findOrFail($id);
    Mail::send(new LateEnterMail($student));
}









}


