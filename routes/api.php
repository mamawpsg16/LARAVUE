<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SideBarController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleAccessController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\AuthenticationController;

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

/** NOTIFICATIONS */
Route::get('notifications',[NotificationController::class,'index']);
Route::get('unread-notifications',[NotificationController::class,'unreadNotifications']);
Route::post('markAsRead',[NotificationController::class,'markAsRead']);
Route::post('markAllAsRead',[NotificationController::class,'markAllAsRead']);
Route::post('updateNotification',[NotificationController::class,'updateNotification']);

Route::get('getModules',[SideBarController::class,'index']);
Route::get('role-access-data',[RoleAccessController::class,'getRoleAccessDetails']);
Route::get('role-access/edit/{role}',[RoleAccessController::class,'getRoleAccessEditDetails']);
Route::get('user-roles/edit/{role}',[UserRoleController::class,'getUserRoleEditDetails']);

/** API RESOURCE */
Route::apiResources([
    'tasks' => TaskController::class, 
    'roles' => RoleController::class,
    'permissions' => PermissionController::class,
    'role-access' => RoleAccessController::class,
    'user-roles' => UserRoleController::class,
    'modules' => ModuleController::class,
]);

Route::get('/login/{provider}', [SocialController::class,'redirectToProvider']);
Route::get('/login/{provider}/callback', [SocialController::class,'handleProviderCallback']);
Route::get('/users',[UserController::class,'getUsers']);
Route::post('/getMatchedUsers',[UserController::class,'getMatchedUsers']);
Route::get('/getRoles',[RoleController::class,'getRoles']);
Route::get('/dashboard',DashboardController::class);
Route::get('/profile',[AuthenticationController::class,'me']);
Route::post('/profile',[ProfileController::class,'update']);
Route::post('/updateTaskStatus',[TaskController::class,'updateTaskStatus']);
Route::post('/logout',[AuthenticationController::class, 'logout'])->name(' logout');

/** COMMENT */
Route::post('/comment',[CommentController::class,'store']);
Route::put('/comment/{task_comment}',[CommentController::class,'update']);
Route::delete('/task/{task_comment}',[CommentController::class,'destroy']);
