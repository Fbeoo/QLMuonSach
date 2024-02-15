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
            $detailRequest = $this->model->where('history_rent_book_id',$requestId)->with('book')->get();
            return $detailRequest;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function countBookBorrowing()
    {
        try {
            $detailHistoryRentBookBorrowing = $this->model->whereHas('historyRentBook',function ($query) {
                $query->where('status',HistoryRentBook::statusBorrowing)
                    ->where('expiration_date','>=',now()->format('Y/m/d'));
            })->get();
            $countBook = 0;
            foreach ($detailHistoryRentBookBorrowing as $detail) {
                $countBook += $detail->quantity;
            }
            return $countBook;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function countBookMissing()
    {
        try {
            $detailHistoryRentBookMissing = $this->model->whereHas('historyRentBook',function ($query) {
                $query->where('status',HistoryRentBook::statusBorrowing)
                    ->where('expiration_date','<',now()->format('Y/m/d'));
            })->get();
            $countBook = 0;
            foreach ($detailHistoryRentBookMissing as $detail) {
                $countBook += $detail->quantity;
            }
            return $countBook;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
