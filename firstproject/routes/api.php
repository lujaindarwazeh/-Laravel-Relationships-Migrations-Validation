<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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


Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);



Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');






Route::apiResource('student', StudentController::class);


Route::delete('/bulkDeleteStudent', [StudentController::class, 'bulkDelete']);









