<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\AuthorBook;
use App\Models\AuthorInfo;
use App\Models\Book;
use App\Models\Category;
use App\Models\CommentBook;
use App\Models\DetailHistoryRentBook;
use App\Models\HistoryRentBook;
use App\Models\ReportCompensation;
use App\Models\User;
use App\Repositories\AdminRepositoryInterface;
use App\Repositories\AuthorBookRepositoryInterface;
use App\Repositories\AuthorInfoRepositoryInterface;
use App\Repositories\BookRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\CommentBookRepositoryInterface;
use App\Repositories\DetailHistoryRentBookRepositoryInterface;
use App\Repositories\Eloquent\AdminRepository;
use App\Repositories\Eloquent\AuthorBookRepository;
use App\Repositories\Eloquent\AuthorInfoRepository;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\BookRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\CommentBookRepository;
use App\Repositories\Eloquent\DetailHistoryRentBookRepository;
use App\Repositories\Eloquent\HistoryRentBookRepository;
use App\Repositories\Eloquent\ReportCompensationRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\HistoryRentBookRepositoryInterface;
use App\Repositories\ReportCompensationRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AdminRepositoryInterface::class,function () {
            return new AdminRepository(new Admin());
        });
        $this->app->singleton(AuthorBookRepositoryInterface::class,function () {
            return new AuthorBookRepository(new AuthorBook());
        });
        $this->app->singleton(AuthorInfoRepositoryInterface::class,function () {
            return new AuthorInfoRepository(new AuthorInfo());
        });
        $this->app->singleton(BookRepositoryInterface::class,function () {
            return new BookRepository(new Book());
        });
        $this->app->singleton(CategoryRepositoryInterface::class,function () {
            return new CategoryRepository(new Category());
        });
        $this->app->singleton(CommentBookRepositoryInterface::class,function () {
            return new CommentBookRepository(new CommentBook());
        });
        $this->app->singleton(DetailHistoryRentBookRepositoryInterface::class,function () {
            return new DetailHistoryRentBookRepository(new DetailHistoryRentBook());
        });
        $this->app->singleton(HistoryRentBookRepositoryInterface::class,function () {
            return new HistoryRentBookRepository(new HistoryRentBook());
        });
        $this->app->singleton(ReportCompensationRepositoryInterface::class,function () {
            return new ReportCompensationRepository(new ReportCompensation());
        });
        $this->app->singleton(UserRepositoryInterface::class,function () {
            return new UserRepository(new User());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
