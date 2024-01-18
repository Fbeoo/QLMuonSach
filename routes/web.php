<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//General

Route::get('/login',function () {
    return view('login');
})->name('login');

Route::post('/login')->middleware('auth.useroradmin');

//User
    // Get
Route::get('/', [BookController::class,'getBookForHomePage'])->name('home')->middleware('auth');

Route::get('/test',function () {
    return view('test');
})->middleware('auth');

    //Post
Route::post('/logout',[UserController::class,'logout'])->name('logout');

//Admin
    //Get
Route::get('/admin/dashboard',function () {
    return view('admin.dashboard');
})->name('dashboard');


