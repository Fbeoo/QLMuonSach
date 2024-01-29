<?php

use App\Http\Controllers\AuthorInfoController;
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

Route::get('/author',[AuthorInfoController::class,'getAllAuthor']);

Route::get('/admin/search/book/{bookName}',[BookController::class,'getBookByName']);

Route::get('/admin/sort/book/year_publish/{type}',[BookController::class,'sortBookByYearPublish']);

Route::get('/admin/filter/book/status/{type}',[BookController::class,'filterBookByStatus']);

Route::get('/admin/filter/book/category_parent/{categoryParentId}',[BookController::class,'filterBookByCategoryParent']);

Route::get('/admin/filter/book/category_children/{categoryChildrenId}',[BookController::class,'filterBookByCategoryChildren']);

Route::get('/admin/filter/book/year_publish/{minYear}/to/{maxYear}',[BookController::class,'getBookByRangeOfYear']);

//POST
Route::post('/admin/add/book',[BookController::class,'addBook'])->name('addBook');

Route::post('/admin/add/author',[AuthorInfoController::class,'addAuthor']);

Route::post('/admin/filter/book/year_publish/range',[BookController::class,'filterBookByRangeOfYear']);

//PUT
Route::post('/admin/edit/book',[BookController::class,'editBook'])->name('editBook');

Route::put('/admin/lock/book',[BookController::class,'lockBook'])->name('lockBook');

Route::put('/admin/unlock/book',[BookController::class,'unlockBook'])->name('unlockBook');


