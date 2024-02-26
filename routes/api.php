<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthorInfoController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HistoryRentBookController;
use App\Http\Controllers\UserController;
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

Route::get('/count/book-in-cart',[BookController::class,'countBookInCart']);

Route::get('/admin/sort/book/year_publish/{type}',[BookController::class,'sortBookByYearPublish']);

Route::get('/admin/filter/book/status/{type}',[BookController::class,'filterBookByStatus']);

Route::get('/admin/filter/book/category_parent/{categoryParentId}',[BookController::class,'filterBookByCategoryParent']);

Route::get('/admin/filter/book/category_children/{categoryChildrenId}',[BookController::class,'filterBookByCategoryChildren']);

Route::get('/admin/filter/book/year_publish/{minYear}/to/{maxYear}',[BookController::class,'getBookByRangeOfYear']);

Route::get('/admin/all/book',[BookController::class,'getBookForPagingInManageBookPage']);

Route::get('/admin/detail-request/{requestId}',[HistoryRentBookController::class,'getDetailRequest']);

Route::get('/admin/all/request-rent-book',[HistoryRentBookController::class,'getAllRequestRentBook']);

Route::get('/admin/all/request-rent-book/{userId}',[HistoryRentBookController::class,'showRequestRentBookOfUser']);

Route::get('/admin/all/request-rent-book/status/borrowing',[HistoryRentBookController::class,'getRequestRentBookHaveStatusBorrowing']);

Route::get('/admin/all/user',[UserController::class,'getAllUser']);

Route::get('/request-borrowing-book/{userId}',[HistoryRentBookController::class,'getRequestBorrowingOfUser']);

//POST
Route::post('/admin/add/book',[BookController::class,'addBook'])->name('addBook');

Route::post('/admin/add/author',[AuthorInfoController::class,'addAuthor']);

Route::post('/admin/filter/book/year_publish/range',[BookController::class,'filterBookByRangeOfYear']);

Route::post('/admin/filter/book',[BookController::class,'filterBook']);

Route::post('/add-to-cart',[BookController::class,'addBookToCart']);

Route::post('/rent-single-book',[BookController::class,'rentSingleBook']);

Route::post('/change-number-book/{line}',[BookController::class,'changeNumberBookInCart']);

Route::post('/remove-book-in-cart/{line}',[BookController::class,'removeBookInCart']);

Route::post('/change-date-rent',[BookController::class,'changeDateRent']);

Route::post('/rent-multi-book',[BookController::class,'rentMultiBook']);

Route::post('/edit-profile',[UserController::class,'editProfile']);

Route::post('/admin/filter/request-rent-book',[HistoryRentBookController::class,'filterRequestRentBook']);

Route::put('/admin/accept-request-rent-book',[HistoryRentBookController::class,'acceptRequestRentBook']);

Route::put('/admin/refuse-request-rent-book',[HistoryRentBookController::class,'refuseRequestRentBook']);

Route::put('/admin/mark-returned-book',[HistoryRentBookController::class,'markReturnedBook']);

Route::post('/confirm-rent-book',[BookController::class,'confirmRentBook']);

Route::post('/validate-rent-single-book',[BookController::class,'validateRentSingleBook']);

Route::post('/validate-rent-multi-book',[BookController::class,'validateRentMultiBook']);

Route::post('/admin/edit/book',[BookController::class,'editBook'])->name('editBook');

Route::post('/admin/filter/user',[UserController::class,'filterUser']);

Route::post('/admin/export-report',[AdminController::class,'exportReport']);

Route::post('/admin/export-invoice',[AdminController::class,'exportInvoice']);

Route::post('/admin/add/book/excel',[BookController::class,'addBookByExcelFile']);

Route::put('/admin/lock/book',[BookController::class,'lockBook'])->name('lockBook');

Route::put('/admin/unlock/book',[BookController::class,'unlockBook'])->name('unlockBook');

Route::put('/admin/lock/user',[UserController::class,'lockUser']);

Route::put('/admin/unlock/user',[UserController::class,'unlockUser']);




