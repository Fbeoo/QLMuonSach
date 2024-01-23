<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
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

//GET
Route::get('/category/parent',[CategoryController::class,'getCategoryParent']);
Route::get('/category/children/{categoryParentId}',[CategoryController::class,'getCategoryChildren']);

//POST
Route::post('/admin/add/book',[BookController::class,'addBook'])->name('addBook');

Route::post('/admin/validate',[BookController::class,'validateInput']);

//PUT
Route::put('/admin/edit/book',[BookController::class,'editBook'])->name('editBook');

Route::put('/admin/lock/book',[BookController::class,'lockBook'])->name('lockBook');

Route::put('/admin/unlock/book',[BookController::class,'unlockBook'])->name('unlockBook');


