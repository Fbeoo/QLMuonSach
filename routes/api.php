<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthorInfoController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentBookController;
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
Route::prefix('admin')->group(function () {
    Route::middleware('auth.check.admin')->group(function () {
        Route::get('/search/book/{bookName}',[BookController::class,'getBookByName']);

        Route::get('/sort/book/year_publish/{type}',[BookController::class,'sortBookByYearPublish']);

        Route::get('/filter/book/status/{type}',[BookController::class,'filterBookByStatus']);

        Route::get('/filter/book/category_parent/{categoryParentId}',[BookController::class,'filterBookByCategoryParent']);

        Route::get('/filter/book/category_children/{categoryChildrenId}',[BookController::class,'filterBookByCategoryChildren']);

        Route::get('/filter/book/year_publish/{minYear}/to/{maxYear}',[BookController::class,'getBookByRangeOfYear']);

        Route::get('/all/book',[BookController::class,'getBookForPagingInManageBookPage']);

        Route::get('/detail-request/{requestId}',[HistoryRentBookController::class,'getDetailRequest']);

        Route::get('/all/request-rent-book',[HistoryRentBookController::class,'getAllRequestRentBook']);

        Route::get('/all/request-rent-book/{userId}',[HistoryRentBookController::class,'showRequestRentBookOfUser']);

        Route::get('/all/request-rent-book/status/borrowing',[HistoryRentBookController::class,'getRequestRentBookHaveStatusBorrowing']);

        Route::get('/all/user',[UserController::class,'getAllUser']);

        Route::post('/add/book',[BookController::class,'addBook'])->name('addBook');

        Route::post('/add/author',[AuthorInfoController::class,'addAuthor']);

        Route::post('/filter/book/year_publish/range',[BookController::class,'filterBookByRangeOfYear']);

        Route::post('/filter/book',[BookController::class,'filterBook']);

        Route::post('/filter/request-rent-book',[HistoryRentBookController::class,'filterRequestRentBook']);

        Route::post('/export-report',[AdminController::class,'exportReport']);

        Route::post('/export-invoice',[AdminController::class,'exportInvoice']);

        Route::post('/add/book/excel',[BookController::class,'addBookByExcelFile']);

        Route::put('/accept-request-rent-book',[HistoryRentBookController::class,'acceptRequestRentBook']);

        Route::put('/refuse-request-rent-book',[HistoryRentBookController::class,'refuseRequestRentBook']);

        Route::put('/mark-returned-book',[HistoryRentBookController::class,'markReturnedBook']);

        Route::post('/filter/user',[UserController::class,'filterUser']);

        Route::put('/lock/book',[BookController::class,'lockBook'])->name('lockBook');

        Route::put('/unlock/book',[BookController::class,'unlockBook'])->name('unlockBook');

        Route::put('/lock/user',[UserController::class,'lockUser']);

        Route::put('/unlock/user',[UserController::class,'unlockUser']);
    });
});

Route::get('/request-borrowing-book/{userId}',[HistoryRentBookController::class,'getRequestBorrowingOfUser']);

Route::get('/comment/book/{bookId}',[CommentBookController::class,'getCommentOfBook']);

Route::get('/category/parent',[CategoryController::class,'getCategoryParent']);

Route::get('/category/children/{categoryParentId}',[CategoryController::class,'getCategoryChildren']);

Route::get('/author',[AuthorInfoController::class,'getAllAuthor']);

Route::get('/detail-request/{requestId}',[HistoryRentBookController::class,'getDetailRequest']);

Route::get('/count/book-in-cart',[BookController::class,'countBookInCart']);

Route::post('/add-to-cart',[BookController::class,'addBookToCart']);

Route::post('/rent-single-book',[BookController::class,'rentSingleBook']);

Route::post('/change-number-book/{line}',[BookController::class,'changeNumberBookInCart']);

Route::post('/remove-book-in-cart/{line}',[BookController::class,'removeBookInCart']);

Route::post('/change-date-rent',[BookController::class,'changeDateRent']);

Route::post('/rent-multi-book',[BookController::class,'rentMultiBook']);

Route::post('/edit-profile',[UserController::class,'editProfile']);

Route::post('/comment/book',[CommentBookController::class,'addComment']);

Route::post('/confirm-rent-book',[BookController::class,'confirmRentBook']);

Route::post('/validate-rent-single-book',[BookController::class,'validateRentSingleBook']);

Route::post('/validate-rent-multi-book',[BookController::class,'validateRentMultiBook']);

Route::post('/admin/edit/book',[BookController::class,'editBook'])->name('editBook');

Route::put('/refuse-request-rent-book',[HistoryRentBookController::class,'refuseRequestRentBook']);





