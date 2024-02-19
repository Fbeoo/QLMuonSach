<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthorBookController;
use App\Http\Controllers\AuthorInfoController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HistoryRentBookController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
//User
    //GET
Route::get('/login',function () {
    return view('login');
})->name('login');

Route::get('/forgot-password',function () {
    return view('forgotPassword');
})->name('forgotPassword');

Route::get('/403', function () {
    return view('error.403');
})->name('403');

Route::get('/register',function () {
    return view('register');
})->name('register');

Route::get('/verify/email/{token}',[UserController::class,'verifyEmail']);

Route::get('/verify/email/notice',function () {
    return view('noticeVerifyEmail');
})->name('noticeVerifyEmail');

Route::get('/forgot-password/notice',function () {
    return view('noticeForgotPassword');
})->name('noticeForgotPassword');

Route::get('/reset-password/{token}',[UserController::class,'redirectToPageResetPassword'])->name('redirectToPageResetPassword');

Route::get('/reset-password',function () {
    return view('resetPassword');
})->name('resetPassword');

Route::post('/register',[UserController::class,'register'])->name('register');

Route::post('/resend/verify/email',[UserController::class,'resendVerifyEmail'])->name('resendVerifyEmail');

Route::post('/forgot-password',[UserController::class,'forgotPassword'])->name('forgotPassword');

Route::post('/reset-password',[UserController::class,'resetPassword'])->name('resetPassword');

Route::post('/resend/reset-password',[UserController::class,'resendResetPassword'])->name('resendResetPassword');

Route::middleware(['auth'])->group(function () {
    // GET

    Route::get('/', [BookController::class,'getBookForHomePage'])->name('home');

    Route::get('/author',[AuthorInfoController::class,'showAuthorInAllAuthorPage'])->name('allAuthor');

    Route::get('/book/{id}',[BookController::class,'getDetailBook'])->name('detail_book');

    Route::get('/book/category/{categoryId}',[BookController::class,'getBookByCategory'])->name('getBookByCategory');

    Route::get('/book',[BookController::class,'showBookInAllBookPage'])->name('allBook');

    Route::get('/author/{authorId}',[AuthorBookController::class,'showBookOfAuthor'])->name('bookOfAuthor');

    Route::get('/cart',[BookController::class,'showBookInCart'])->name('cart');

    Route::get('/history/{userId}',[HistoryRentBookController::class,'showHistoryRentBook'])->name('historyRentBook');

    Route::get('/calendar-return-book',[HistoryRentBookController::class,'showCalendarReturnBook'])->name('calendarReturnBook');

    Route::get('/account',function () {
        return view('userInformation');
    })->name('profile');

    Route::post('/confirm-rent-book',[BookController::class,'confirmRentBook'])->name('confirmRentBook');

    Route::post('/search/book',[BookController::class,'searchBook'])->name('searchBook');

});

    //POST
Route::post('/login')->middleware('auth.user')->name('login');

Route::post('/logout',[UserController::class,'logout'])->name('logout');

//Admin
Route::prefix('admin')->group(function () {
    //GET
    Route::get('/login',function () {
        return view('admin.login');
    })->name('admin.login');

    //POST
    Route::post('/login')->middleware('auth.admin')->name('admin.login');

    Route::post('/logout',[AdminController::class,'logout'])->name('admin.logout');
    Route::middleware('auth.check.admin')->group(function () {
        //GET
        Route::get('/dashboard',[AdminController::class,'showDashBoard'])->name('dashboard');

        Route::get('/manage/book',[BookController::class,'getBookForManageBookPage'])->name('manageBook');

        Route::get('/edit/book/{bookId}',[BookController::class,'getBookEdit'])->name('editBookPage');

        Route::get('/add/book',[BookController::class,'addBookPage'])->name('addBookPage');

        Route::get('/request/rent-book',[HistoryRentBookController::class,'showRequestRentBook'])->name('requestRentBook');

        Route::get('/manage/user',[UserController::class,'showAllUser'])->name('manageUser');

        Route::get('/calendar',function () {
            return view('admin.calendarPage');
        })->name('calendarPage');

        Route::get('/report',function () {
            return view('admin.exportReport');
        })->name('report');
        //POST

        //PUT


    });
});





Route::get('users/export/', [AdminController::class, 'exportReport']);
