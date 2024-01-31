<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HistoryRentBook;
use App\Repositories\HistoryRentBookRepositoryInterface;
use Illuminate\Http\Request;

class HistoryRentBookController extends Controller
{
    protected $historyRentBookRepository;

    /**
     * @param $historyRentBookRepository
     */
    public function __construct(HistoryRentBookRepositoryInterface $historyRentBookRepository)
    {
        $this->historyRentBookRepository = $historyRentBookRepository;
    }

    public function showHistoryRentBook($userId) {
        try {
            $historyRentBook = $this->historyRentBookRepository->getHistoryRentBookOfUser($userId);
            return view('historyRentBook',['historyRent'=>$historyRentBook]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function showRequestRentBook() {
        try {
            $requestRent = $this->historyRentBookRepository->getHistoryRentBookForAdmin();
            return view('admin.requestRentBook',['requestRent'=>$requestRent]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function acceptRequestRentBook(Request $request) {
        try {
            $requestRentBook = $this->historyRentBookRepository->find($request->input('requestRentBookId'));
            if (!$requestRentBook) {
                return response()->json(['error'=>'Không tìm thấy yêu cầu mượn sách']);
            }
            $requestRentBook->status = HistoryRentBook::statusBorrowing;
            $this->historyRentBookRepository->update($requestRentBook);
            return response()->json(['success'=>'Chấp nhận  yêu cầu mượn sách thành công']);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
