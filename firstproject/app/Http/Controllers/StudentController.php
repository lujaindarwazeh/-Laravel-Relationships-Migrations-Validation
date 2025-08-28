<?php

namespace App\Http\Controllers;



use App\Models\Country;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\updateredis;
use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;
use App\Http\Requests\updatestudent;
use App\Mail\LateEnterMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Redis;





class StudentController extends Controller
{
    
   public function store(StoreStudentRequest $request)
{

     $student = new Student();
     $student->fill($request->all());
     $student->save();



    $date = now();
    

    $key = "student->id:{$student->id},{$date->toDateString()}";

      $studentData = [
        'id'         => $student->id,
        'first_name' => $student->first_name,
        'last_name'  => $student->last_name,
        'email'      => $student->email,
        'date' => $date->toDateString(),  
 
        
    ];

   
    Redis::set($key, json_encode($studentData));




    return response()->json([
        'success' => true,
        'message' => 'Student created successfully',
        'student' => new StudentResource($student),
    ], 200);


}


public function bettwenstudentredis($firstdate, $enddate)
{
    try {
       
        if ($firstdate > $enddate) {
            return response()->json([
                'status' => false,
                'message' => 'Start date must be before or equal to end date'
            ], 422);
        }

        $prefix = config('database.redis.options.prefix') ?? '';

       
      
        $keys = Redis::keys("*student->id:*");

        log:info('Found Redis keys: ', ['keys' => $keys]);

        

     

        $students = [];

        foreach ($keys as $key) {
           
            $realKey = str_replace($prefix, '', $key);

       

            $studentDataJson = Redis::get($realKey);
            if (!$studentDataJson) {
                echo'No data found for key.', ['key' => "$realKey"];
                continue;
            }

            $studentData = json_decode($studentDataJson, true);
            if (!$studentData) {
                echo'Failed to decode JSON for key.', ['key' => "$realKey"];
                continue;
            }

            $studentDate = \Carbon\Carbon::parse($studentData['date']);
            $start = \Carbon\Carbon::parse($firstdate);
            $end   = \Carbon\Carbon::parse($enddate);

            if ($studentDate->between($start, $end)) {
                $students[] = $studentData;
            }
        }

        if (empty($students)) {
            return response()->json([
                'status' => false,
                'message' => "No students found in Redis between {$firstdate} and {$enddate}"
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "Students between {$firstdate} and {$enddate}",
            'data' => $students
        ], 200);

    } catch (\Exception $e) {
        echo'Error fetching students from Redis: ' . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString()
        ];

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong while fetching data from Redis'
        ], 500);
    }
}








public function getFromRedis($studentid,$startdate,$enddate)
{
  $key = "student->id:{$studentid},{$startdate},{$enddate}";
    $studentData = Redis::get($key);

    if ($studentData) {
        return response()->json([
            'success' => true,
            'student' => json_decode($studentData, true),
            'message' => 'Student retrieved from Redis successfully',
        ], 200);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Student not found in Redis',
        ], 404);
    }   
   
}




public function updateInRedis(updateredis $request, $studentid, $startdate, $enddate)
{
    
    $oldKey = "student->id:{$studentid},{$startdate},{$enddate}";

    
    if (Redis::exists($oldKey)) {
        Redis::del($oldKey);
    }

 
    $newData = $request->all();

    
    $newKey = "student->id:{$studentid},{$newData['start_date']},{$newData['end_date']}";

   
    Redis::set($newKey, json_encode($newData));

    return response()->json([
        'success' => true,
        'message' => "Student updated in Redis. Old key deleted: {$oldKey}, new key stored: {$newKey}",
        'data'    => $newData
    ], 200);
}



public function deleteFromRedis($studentid, $startdate, $enddate)
{
    $key = "student->id:{$studentid},{$startdate},{$enddate}";

       if (Redis::exists($key)) {
        Redis::del($key);
           return response()->json([
            'success' => true,
            'message' => 'Student deleted from Redis successfully',
        ], 200);
    }

 
     
        else {
        return response()->json([
            'success' => false,
            'message' => 'Student not found in Redis',
        ], 404);
    }
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



public function getcountstudents()
{
    $count = Student::count();

    return response()->json([
        'success' => true,
        'count' => $count,
        'message' => 'Total students count retrieved successfully',
    ], 200);
}









public function getallstudents()
{
    $students = Student::all();

    return response()->json([
        'success' => true,
        'students' => StudentResource::collection($students),
        'message' => 'All students retrieved successfully',
    ], 200);
}




public function sendLateEntryNotification($id)
{
    $student = Student::findOrFail($id);
    Mail::send(new LateEnterMail($student));
}





}


