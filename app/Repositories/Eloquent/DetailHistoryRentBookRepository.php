<?php
namespace App\Repositories\Eloquent;
use App\Models\HistoryRentBook;
use App\Repositories\DetailHistoryRentBookRepositoryInterface;

class DetailHistoryRentBookRepository extends BaseRepository implements DetailHistoryRentBookRepositoryInterface {

    public function getNumberOfBookRenting($bookId)
    {
        try {
            $detailHistoryRentBook = $this->model
                ->where('book_id',$bookId)
                ->whereHas('historyRentBook', function ($query) {
                    $query->where('status',HistoryRentBook::statusBorrowing);
                })->get();
            return $detailHistoryRentBook;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
