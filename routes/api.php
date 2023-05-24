<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthenticationController;

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

Route::get('post',function(){
    return response()->json('WOW GRAPE');
});

// Route::get('/shit',[AuthenticationController::class, 'shit'])->name('register.shit');
Route::post('/register',[AuthenticationController::class, 'registerStore'])->name(' register.store');
Route::post('/login',[AuthenticationController::class, 'authenticate'])->name(' login');
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     Route::apiResource('tasks', TaskController::class);
//     // Route::get('/', function(){
//     //     return response()->json([]);
//     // });
//     return $request->user();
// });

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/user', function (Request $request) {
//         echo 'SHEEESH';
//         return $request->user();
//     });
   
// });
Route::apiResource('tasks', TaskController::class);
Route::get('/users',[UserController::class,'getUsers']);
Route::get('/profile',[AuthenticationController::class,'me']);
Route::post('/profile',[ProfileController::class,'update']);
Route::post('/updateTaskStatus',[TaskController::class,'updateTaskStatus']);
Route::post('/logout',[AuthenticationController::class, 'logout'])->name(' logout');
