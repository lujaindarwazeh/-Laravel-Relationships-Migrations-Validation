<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SessionController;
use \Illuminate\Session\Middleware\StartSession;
use App\Http\Controllers\countrycontrolLer;
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






Route::apiResource('student', StudentController::class);


Route::delete('/bulkDeleteStudent', [StudentController::class, 'bulkDelete']);









