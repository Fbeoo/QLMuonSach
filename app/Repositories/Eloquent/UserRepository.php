<?php
namespace App\Repositories\Eloquent;
use App\Models\HistoryRentBook;
use App\Repositories\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface {

    public function getAllUser()
    {
        try {
            $user = $this->model->withCount([
                'historyRentBook as requestStatusPending' => function($query) {
                    $query->where('status',HistoryRentBook::statusPending);
                },
                'historyRentBook as requestStatusBorrowing' => function($query) {
                    $query->where('status',HistoryRentBook::statusBorrowing);
                },
                'historyRentBook as requestStatusReturned' => function($query) {
                    $query->where('status',HistoryRentBook::statusReturned);
                },
                'historyRentBook as requestStatusRefuse' => function($query) {
                    $query->where('status',HistoryRentBook::statusRefuse);
                }
            ])->paginate('10');
            return $user;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
