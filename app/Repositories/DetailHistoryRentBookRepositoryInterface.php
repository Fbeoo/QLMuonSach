<?php
namespace App\Repositories;
interface DetailHistoryRentBookRepositoryInterface extends EloquentRepositoryInterface {
    public function getNumberOfBookRenting($bookId);

    public function getDetailRequestRentBook($requestId);

    public function countBookBorrowing();

    public function countBookMissing();

}
