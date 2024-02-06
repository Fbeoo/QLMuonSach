<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\BookRepositoryInterface;
use App\Repositories\DetailHistoryRentBookRepositoryInterface;
use App\Repositories\HistoryRentBookRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
