<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\AuthorBookRepositoryInterface;
use Illuminate\Http\Request;

class AuthorBookController extends Controller
{
    protected $authorBookRepository;

    /**
     * @param $authorBookRepository
     */
    public function __construct(AuthorBookRepositoryInterface $authorBookRepository)
    {
        $this->authorBookRepository = $authorBookRepository;
    }
    public function showBookOfAuthor($authorId) {
        try {
            $authorAndBook = $this->authorBookRepository->getBookOfAuthor($authorId);
            return view('bookOfAuthor',['authorAndBookInfo'=>$authorAndBook]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
