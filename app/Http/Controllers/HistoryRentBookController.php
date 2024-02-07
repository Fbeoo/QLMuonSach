<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HistoryRentBook;
use App\Repositories\BookRepositoryInterface;
use App\Repositories\DetailHistoryRentBookRepositoryInterface;
use App\Repositories\Eloquent\BookRepository;
use App\Repositories\HistoryRentBookRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryRentBookController extends Controller
{
    protected $historyRentBookRepository;

    protected $detailHistoryRentBookRepository;

    protected $bookRepository;

    /**
     * @param $historyRentBookRepository
     */
    public function __construct(HistoryRentBookRepositoryInterface $historyRentBookRepository, DetailHistoryRentBookRepositoryInterface $detailHistoryRentBookRepository, BookRepositoryInterface $bookRepository)
    {
        $this->historyRentBookRepository = $historyRentBookRepository;
        $this->detailHistoryRentBookRepository = $detailHistoryRentBookRepository;
        $this->bookRepository = $bookRepository;
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

    public function showRequestRentBookOfUser($userId) {
        try {
            $requestRentBook = $this->historyRentBookRepository->getRequestRentBookOfUser($userId);
            return $requestRentBook;
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function getAllRequestRentBook() {
        try {
            $requestRentBook =  $this->historyRentBookRepository->getHistoryRentBookForAdmin();
            return $requestRentBook;
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function acceptRequestRentBook(Request $request) {
        try {
            $requestRentBook = $this->historyRentBookRepository->find($request->input('requestId'));
            if (!$requestRentBook) {
                return response()->json(['error'=>'Không tìm thấy yêu cầu mượn sách']);
            }
            $detailRequest = $this->detailHistoryRentBookRepository->getDetailRequestRentBook($request->input('requestId'));
            foreach ($detailRequest as $detail) {
                $numberBookRenting = 0;
                $bookRent = $this->bookRepository->find($detail->book_id);
                $bookRenting = $this->detailHistoryRentBookRepository->getNumberOfBookRenting($detail->book_id);
                foreach ($bookRenting as $renting) {
                    $numberBookRenting += $renting->quantity;
                }
                if ($bookRent->quantity - $numberBookRenting < $detail->quantity) {
                    return response()->json(['errorQuantity'=>'Số sách trong kho không đủ đáp ứng yêu cầu mượn']);
                }
            }
            $requestRentBook->status = HistoryRentBook::statusBorrowing;
            $this->historyRentBookRepository->update($requestRentBook);
            return response()->json(['success'=>'Chấp nhận  yêu cầu mượn sách thành công']);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function refuseRequestRentBook(Request $request) {
        try {
            $requestRentBook = $this->historyRentBookRepository->find($request->input('requestId'));
            if (!$requestRentBook) {
                return response()->json(['error'=>'Không tìm thấy yêu cầu mượn sách']);
            }
            $requestRentBook->status = HistoryRentBook::statusRefuse;
            $this->historyRentBookRepository->update($requestRentBook);
            return response()->json(['success'=>'Từ chối yêu cầu mượn sách thành công']);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function markReturnedBook(Request $request) {
        try {
            $requestRentBook = $this->historyRentBookRepository->find($request->input('requestId'));
            if (!$requestRentBook) {
                return response()->json(['error'=>'Không tìm thấy yêu cầu mượn sách']);
            }
            $requestRentBook->status = HistoryRentBook::statusReturned;
            $requestRentBook->return_date = now()->format('Y/m/d');
            $this->historyRentBookRepository->update($requestRentBook);
            return response()->json(['success'=>'Đánh dấu người mượn trả sách thành công']);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getDetailRequest($requestId) {
        try {
            $detailRequest = $this->historyRentBookRepository->getDetailRequestRentBook($requestId);
            return $detailRequest;
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function filterRequestRentBook (Request $request) {
        try {
            $minDateRent = $maxDateRent = $minDateReturn = $maxDateReturn = '';
            if ($request->input('dateRentRange') !== null) {
                $dateRentRange = $request->input('dateRentRange');
                $dateRent = explode(' - ',$dateRentRange);
                $minDateRent = Carbon::createFromFormat('d/m/Y',$dateRent[0])->format('Y/m/d');
                $maxDateRent = Carbon::createFromFormat('d/m/Y',$dateRent[1])->format('Y/m/d');
            }
                if ($request->input('dateReturnRange') !== null) {
                $dateReturnRange = $request->input('dateReturnRange');
                $dateReturn = explode(' - ',$dateReturnRange);
                $minDateReturn = Carbon::createFromFormat('d/m/Y',$dateReturn[0])->format('Y/m/d');
                $maxDateReturn = Carbon::createFromFormat('d/m/Y',$dateReturn[1])->format('Y/m/d');
            }
            $arrFilter = [
                'userEmail' => $request->input('userEmail'),
                'requestStatus' => $request->input('requestStatus'),
                'minDateRent' => $minDateRent,
                'maxDateRent' => $maxDateRent,
                'minDateReturn' => $minDateReturn,
                'maxDateReturn' => $maxDateReturn
            ];
            $resultFilter = $this->historyRentBookRepository->filterRequestRentBook($arrFilter);
            return $resultFilter;
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function getRequestRentBookHaveStatusBorrowing() {
        try {
            $requestRentBook = $this->historyRentBookRepository->getRequestRentBookHaveStatusBorrowing();
            return $requestRentBook;
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function showCalendarReturnBook() {
        try {
            return view('calendarReturnBook');
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getRequestBorrowingOfUser($userId) {
        try {
            $requestRentBook = $this->historyRentBookRepository->getRequestBorrowingOfUser($userId);
            return $requestRentBook;
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

}
