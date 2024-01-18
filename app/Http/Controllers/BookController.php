<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\BookRepositoryInterface;
use App\Repositories\Eloquent\BookRepository;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $bookRepository;

    /**
     * @param $bookRepository
     */
    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }
    public function getBookForHomePage() {
        return view('index',['books'=>$this->bookRepository->getALl()]);
    }
}
