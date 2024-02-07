<?php
namespace App\Repositories;
interface HistoryRentBookRepositoryInterface extends EloquentRepositoryInterface {
    public function getHistoryRentBookOfUser($userId);

    public function getHistoryRentBookForAdmin();

    public function getDetailRequestRentBook($requestId);

    public function filterRequestRentBook($arrFilter);

    public function getRequestRentBookOfUser($userId);

    public function getRequestRentBookHaveStatusBorrowing();

    public function countRequestRentBookHaveStatusPending();

    public function getRequestBorrowingOfUser($userId);
}
