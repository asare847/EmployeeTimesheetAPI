<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
Route::controller(AuthController::class)->group(function(){
    Route::post('/register','register');
    Route::post('/login','login');
    Route::post('/logout','logout')->middleware('auth:sanctum');
    Route::post('/reset-password','resetPassword')->middleware('auth:sanctum');
});
Route::middleware('auth:sanctum')->controller(UserController::class)->group(function(){
    Route::get('/users','index');
    Route::get('/users/{id}','show');
    Route::put('/users/{id}','update');
    Route::delete('/users/{id}','destroy');
});

Route::middleware('auth:sanctum')->controller(AttendanceController::class)->group(function(){
    Route::post('/attendance/clock_in','clock_in');
    Route::post('/attendance/clock_out','clock_out'); 
    Route::get('/attendance/reports/{id}','reports');
    Route::get('/attendance/reports','all_reports');
});


