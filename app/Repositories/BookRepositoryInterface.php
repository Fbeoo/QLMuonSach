<?php
namespace App\Repositories;
interface BookRepositoryInterface extends EloquentRepositoryInterface {
    public function getBookForPaging();
    public function getBookByCategory($categoryId);
}
