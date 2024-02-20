<?php

namespace App\Http\Controllers;

use App\Exports\ExportCountBookMissing;
use App\Exports\ExportCountBookRent;
use App\Exports\ExportRequestRentBookInDay;
use App\Http\Controllers\Controller;
use App\Repositories\BookRepositoryInterface;
use App\Repositories\DetailHistoryRentBookRepositoryInterface;
use App\Repositories\HistoryRentBookRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

/**
 *
 */
class AdminController extends Controller
{

    protected $historyRentBookRepository;

    protected $userRepository;

    protected $detailHistoryRentBookRepository;

    protected $bookRepository;

    /**
     * @param $historyRentBookRepository
     */
    public function __construct(HistoryRentBookRepositoryInterface $historyRentBookRepository,
                                UserRepositoryInterface $userRepository,
                                DetailHistoryRentBookRepositoryInterface $detailHistoryRentBookRepository,
                                BookRepositoryInterface $bookRepository
    )
    {
        $this->historyRentBookRepository = $historyRentBookRepository;
        $this->userRepository = $userRepository;
        $this->detailHistoryRentBookRepository = $detailHistoryRentBookRepository;
        $this->bookRepository = $bookRepository;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function showDashBoard() {
        try {
            $countRequestRentBookHaveStatusPending = $this->historyRentBookRepository->countRequestRentBookHaveStatusPending();
            $countUser = $this->userRepository->countUser();
            $countTotalBook = $this->bookRepository->countTotalBook();
            $countBookBorrowing = $this->detailHistoryRentBookRepository->countBookBorrowing();
            $countBookMissing = $this->detailHistoryRentBookRepository->countBookMissing();
            $countBookAvailable = $countTotalBook-$countBookMissing-$countBookBorrowing;
            return view('admin.dashboard',[
                'countRequestRentBookHaveStatusPending' => $countRequestRentBookHaveStatusPending,
                'countUser' => $countUser,
                'countBookAvailable' => $countBookAvailable,
                'countBookBorrowing' => $countBookBorrowing,
                'countBookMissing' => $countBookMissing
            ]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function exportReport(Request $request) {
        try {
            if ($request->input('typeReport') === 'bookRent') {
                $dateReportRange = $request->input('dateRangeReport');
                $dateReport = explode(' - ',$dateReportRange);
                $minDate = Carbon::createFromFormat('d/m/Y',$dateReport[0])->format('Y/m/d');
                $maxDate = Carbon::createFromFormat('d/m/Y',$dateReport[1])->format('Y/m/d');
                $book = $this->bookRepository->getStatiѕticsOfBook($minDate,$maxDate);
                return Excel::download(new ExportCountBookRent($book,$dateReport[0],$dateReport[1]), 'report.xlsx');
            }
            else if ($request->input('typeReport') === 'bookMissing') {
                $book = $this->bookRepository->getInformationBookMissing();
                return Excel::download(new ExportCountBookMissing($book),'report.xlsx');
            }
            else if ($request->input('typeReport') === 'requestRentInDay') {
                $requestRentBook = $this->historyRentBookRepository->getRequestRentBookInDay();
                return Excel::download(new ExportRequestRentBookInDay($requestRentBook),'report.xlsx');
            }
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }
}
