<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


 


Route::post('/store', [StudentController::class, 'store']);
Route::get('/student/{id}', [StudentController::class, 'show']);

Route::get('/students', [StudentController::class, 'index']);
   
    
Route::post('/store-course', [CourseController::class, 'store']);

