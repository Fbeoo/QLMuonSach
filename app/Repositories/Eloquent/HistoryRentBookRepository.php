<?php
namespace App\Repositories\Eloquent;
use App\Repositories\HistoryRentBookRepositoryInterface;

class HistoryRentBookRepository extends BaseRepository implements HistoryRentBookRepositoryInterface {

    public function getHistoryRentBookOfUser($userId)
    {
        try {
            $historyRent = $this->model->where('user_id',$userId)->get();
            return $historyRent;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getHistoryRentBookForAdmin()
    {
        try {
            $historyRent = $this->model->with('user')->get();
            return $historyRent;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getDetailRequestRentBook($requestId)
    {
        try {
            $detailRequest = $this->model->where('id',$requestId)->with('user','detailHistoryRentBook','detailHistoryRentBook.book')->get();
            return $detailRequest;
        }catch (\Exception $e) {
            dd($e);
            throw new \Exception($e);
        }
    }
}
