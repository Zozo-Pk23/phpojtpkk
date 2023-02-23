<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

Route::post('/login', [ProfileController::class, 'login']);
Route::post('/profile/forgotpassword', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::middleware('auth:api')->group(function () {
    Route::get('/getUser', [ProfileController::class, 'userInfo']);
    Route::resource('users', ProfileController::class);

    Route::resource('profile', ProfileController::class);
    Route::resource('blogs', BlogController::class);

    Route::post('/profile/create', [ProfileController::class, 'create']);
    Route::post('/profile/confirm', [ProfileController::class, 'confirm']);
    Route::post('/profile/delete', [ProfileController::class, 'destroy']);
    Route::post('/profile/edit', [ProfileController::class, 'edit']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::post('/profile/passwordreset', [ProfileController::class, 'passwordreset']);

    Route::get('/blogs/down', [BlogController::class, 'download']);
    Route::post('/blogs/store', [BlogController::class, 'store']);
    Route::post('/blogs/createsomething', [BlogController::class, 'something']);
    Route::post('/blogs/delete', [BlogController::class, 'destroy']);
    Route::post('/logout', [ProfileController::class, 'logout']);

    Route::post('/blogs/update', [BlogController::class, 'update']);
    Route::post('/blogs/upload', [BlogController::class, 'upload']);
    Route::post('/blogs/edit', [BlogController::class, 'edit']);
});

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
