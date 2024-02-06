<?php
namespace App\Repositories\Eloquent;
use App\Models\HistoryRentBook;
use App\Repositories\HistoryRentBookRepositoryInterface;
use function MongoDB\BSON\toRelaxedExtendedJSON;
use function PHPUnit\TestFixture\func;

class HistoryRentBookRepository extends BaseRepository implements HistoryRentBookRepositoryInterface {

    public function getHistoryRentBookOfUser($userId)
    {
        try {
            $historyRent = $this->model->where('user_id',$userId)->paginate('10');
            return $historyRent;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getHistoryRentBookForAdmin()
    {
        try {
            $historyRent = $this->model->with('user')->paginate('10');
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
            throw new \Exception($e);
        }
    }

    public function filterRequestRentBook($arrFilter)
    {
        try {
            $requestFilter = $this->model;
            if ($arrFilter['userEmail'] !== null) {
                $requestFilter = $requestFilter->whereHas('user',function ($query) use($arrFilter) {
                    $query->where('mail',$arrFilter['userEmail']);
                });
            }
            if ($arrFilter['requestStatus'] !== null) {
                $requestFilter = $requestFilter->where('status',$arrFilter['requestStatus']);
            }
            if ($arrFilter['minDateRent'] !== "" && $arrFilter['maxDateRent'] !== "") {
                $requestFilter = $requestFilter->whereBetween('rent_date',[$arrFilter['minDateRent'],$arrFilter['maxDateRent']]);
            }
            if ($arrFilter['minDateReturn'] !== "" && $arrFilter['maxDateReturn'] !== "") {
                $requestFilter = $requestFilter->whereBetween('expiration_date',[$arrFilter['minDateReturn'],$arrFilter['maxDateReturn']]);
            }
            return $requestFilter->with('user')->paginate('10');
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getRequestRentBookOfUser($userId)
    {
        try {
            $requestRentBook = $this->model->where('user_id',$userId)->paginate(5);
            return $requestRentBook;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getRequestRentBookHaveStatusBorrowing()
    {
        try {
            $requestRentBook = $this->model->where('status',HistoryRentBook::statusBorrowing)->with('user')->get();
            return $requestRentBook;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function countRequestRentBookHaveStatusPending() {
        try {
            $countRequest = $this->model->where('status',HistoryRentBook::statusPending)->count();
            return $countRequest;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
