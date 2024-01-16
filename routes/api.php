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
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//STUDENTS ROUTES
Route::post('/students', [StudentController::class, 'store']);
Route::put('/students/{id}', [StudentController::class, 'update']);
Route::delete('/students/{id}', [StudentController::class, 'destroy']);
Route::get('/students/{id}/courses', [StudentController::class, 'getCourses']);
Route::get('/students/{id}', [StudentController::class, 'show']);
Route::get('/students/{query}', [StudentController::class, 'search']);
Route::get('/students', [StudentController::class, 'index']);

//COURSES ROUTES
Route::post('/courses', [CourseController::class, 'store']);
Route::put('/courses/{id}', [CourseController::class, 'update']);
Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
Route::get('courses/{id}/students', [CourseController::class, 'getStudents']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::get('/courses/{query}', [CourseController::class, 'search']);
Route::get('/courses', [CourseController::class, 'index']);
