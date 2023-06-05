<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleAndPermissionController;
use App\Http\Controllers\OrderController;



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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//API route for register new user
Route::post('/register', [AuthController::class, 'register']);
//API route for login user
Route::post('/login', [AuthController::class, 'login']);

Route::get('/get-users',[AuthController::class, 'getAllUser']);

Route::get('driver', [RoleAndPermissionController::class, 'getdriver']);



//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });

    // API route for logout user
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::delete('delete/{id}', [AuthController::class, 'delete']);
    Route::post('update/{id}', [AuthController::class, 'update']);

    Route::get('/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');

    Route::post('driver/{id}', [RoleAndPermissionController::class, 'upDriver']);
    Route::post('customer/{id}', [RoleAndPermissionController::class, 'upCustomer']);

    Route::group(['middleware' => ['role:customer']], function () {
        Route::post('request', [OrderController::class, 'sendRequestOrder']);
    }); 
    
    Route::group(['middleware' => ['role:driver']], function () {
        Route::post('accept', [OrderController::class, 'acceptOrder']);
    }); 
});

