<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [PostController::class, 'index'])->name('home');

Route::get('/createpost', function () {
    return view('posts/createpost');
});
Route::get('/updatepost/{id}', [PostController::class, 'edit'])->name('updatepost');
Route::post('create', [PostController::class, 'save']);
Route::post('confirm', [PostController::class, 'confirm']);
Route::post('/search', [PostController::class, 'search']);
Route::post('/updateblade/{id}', [PostController::class, 'updateblade'])->name('updateblade');
Route::post('/update/{id}', [PostController::class, 'update'])->name('update');
Route::delete('/delete/{id}', [PostController::class, 'delete']);
Route::get('/profile/{id}', [PostController::class, 'profile']);
Route::get('/users', function () {
    return view('users.users');
});
Route::get('/createuser', function () {
    return view('users/createuser');
});
