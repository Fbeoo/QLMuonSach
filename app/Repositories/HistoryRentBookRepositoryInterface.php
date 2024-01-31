<?php
namespace App\Repositories;
interface HistoryRentBookRepositoryInterface extends EloquentRepositoryInterface {
    public function getHistoryRentBookOfUser($userId);

    public function getHistoryRentBookForAdmin();
}
