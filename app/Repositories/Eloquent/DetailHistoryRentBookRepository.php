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

    public function getDetailRequestRentBook($requestId)
    {
        try {
            $detailRequest = $this->model->where('history_rent_book_id',$requestId)->get();
            return $detailRequest;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
