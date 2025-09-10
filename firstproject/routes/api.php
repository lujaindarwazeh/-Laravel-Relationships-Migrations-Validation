<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SessionController;
use \Illuminate\Session\Middleware\StartSession;
use App\Http\Controllers\countrycontrolLer;
use App\Http\Middleware\RequestDurationMiddleware;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\SlowRequest;
use Illuminate\Support\Facades\Mail;




Route::post('/prometheus-alert', function(Request $request) {
    $alertData = $request->all();
    Log::error('Prometheus Alert Received', $alertData);

    // Check for MySQLDown alert
    foreach ($alertData['alerts'] as $alert) {
        if ($alert['labels']['alertname'] === 'MySQLDown') {
            // Run your bash script to terminate commands
            exec(base_path('scripts/stop_commands.bat'));

        }
    }

    return response()->json(['status'=>'ok']);
});




Route::get('/redis-test', function () {
    Redis::set('name', 'Lujain');
    return Redis::get('name'); 
});








//  Route::get('/metrics', function () {
//     $adapter = new InMemory(); // try Redis() if you want persistence
//     $registry = new CollectorRegistry($adapter);

//     $renderer = new RenderTextFormat();
//     $metrics = $registry->getMetricFamilySamples();

//     return response(
//         $renderer->render($metrics),
//         200,
//         ['Content-Type' => RenderTextFormat::MIME_TYPE]
//     );
// });



// Route::get('/metrics', function () {

    
//     $adapter = new Redis([
//         'host' => '127.0.0.1',
//         'port' => 6379,
//     ]);
//     $registry = new CollectorRegistry($adapter);

//     $renderer = new RenderTextFormat();
//     $metrics = $registry->getMetricFamilySamples();

//     return response(
//         $renderer->render($metrics),
//         200,
//         ['Content-Type' => RenderTextFormat::MIME_TYPE]
//     );
// });





Route::get('/studentredis/{studentid}/{startdate}/{enddate}',[StudentController::class,'getFromRedis']);
Route::put('/studentredis/{studentid}/{startdate}/{enddate}',[StudentController::class,'updateInRedis']);
Route::delete('/studentredis/{studentid}/{startdate}/{enddate}',[StudentController::class,'deleteFromRedis']);





Route::get('/bettwenstudentredis/{firstdate}/{enddate}',[StudentController::class,'bettwenstudentredis']);



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('course', CourseController::class);





Route::post('/addcourse', [CourseController::class, 'addCourse']);





Route::middleware(['isverified'])->group(function () {
    Route::get('courses', [CourseController::class, 'getallcourses']);
});



Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('students', [StudentController::class, 'getallstudents']);
});


Route::middleware(['auth:sanctum','role:admin'])->group(function () {
    Route::get('countstudents', [StudentController::class, 'getcountstudents']);
});



Route::middleware(['auth:sanctum','permission:view student'])->group(function () {
    Route::get('viewstudents', [StudentController::class, 'getallstudents']);
});


Route::middleware(['auth:sanctum','role_or_permission:admin,view student'])->group(function () {
    Route::get('viewstudents', [StudentController::class, 'getallstudents']);
});


Route::middleware([\Illuminate\Session\Middleware\StartSession::class, 'auth:sanctum'])->group(function () {
    Route::post('setuserdata', [SessionController::class, 'setuserdata']);
    Route::get('getuserdata', [SessionController::class, 'getuserdata']);
    Route::post('deleteuserdata', [SessionController::class, 'deleteuserdata']);
});



Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('allcountries', [countrycontrolLer::class, 'allcountries']);
    Route::post('createCountry', [countrycontrolLer::class, 'createCountry']);
 
});






Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);



Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::delete('/deleteuser', [AuthController::class, 'deleteuser'])->middleware('auth:sanctum');






Route::apiResource('student', StudentController::class);


Route::delete('/bulkDeleteStudent', [StudentController::class, 'bulkDelete']);


//////////////////////
Route::get('/test/slow-request', function () {
    sleep(10);
    return response()->json(['message' => 'Slow request done!']);
});



Route::get('/test/exception', function () {
    throw new \Exception('Test Exception for logging');
});

Route::get('/test/db-slow', function () {
    DB::select('SELECT SLEEP(3)');
    return response()->json(['message' => 'Slow DB query done!']);
});

Route::get('/test/http-timeout', function () {
    try {
        Http::timeout(1)->get('https://httpbin.org/delay/5');
    } catch (\Exception $e) {
        Log::error('HTTP Timeout Test', ['error' => $e->getMessage()]);
    }
    return response()->json(['message' => 'HTTP Timeout test done!']);
});























// $router->get('/test/slow-request', function () {
//     usleep(10000000); // 
//     return response()->json(['message' => 'Slow request done!']);
// });

// $router->get('/test/exception', function () {
//     throw new \Exception('Test Exception for logging');
// });

// $router->get('/test/db-slow', function () {
//     DB::select('SELECT SLEEP(10)');
//     return response()->json(['message' => 'Slow DB query done!']);
// });

// $router->get('/test/http-timeout', function () {
//     try {
//         Http::timeout(1)->get('https://httpbin.org/delay/5'); // 5s delay → timeout
//     } catch (\Exception $e) {
//         Log::error('HTTP Timeout Test', ['error' => $e->getMessage()]);
//     }
//     return response()->json(['message' => 'HTTP Timeout test done!']);
// }); 










