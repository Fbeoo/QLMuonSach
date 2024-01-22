<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Repositories\AuthorBookRepositoryInterface;
use App\Repositories\AuthorInfoRepositoryInterface;
use App\Repositories\BookRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\Eloquent\BookRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 *
 */
class BookController extends Controller
{
    /**
     * @var BookRepositoryInterface
     */
    protected $bookRepository;
    /**
     * @var AuthorBookRepositoryInterface
     */
    protected $authorBookRepository;
    /**
     * @var AuthorInfoRepositoryInterface
     */
    protected $authorInfoRepository;
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @param BookRepositoryInterface $bookRepository
     * @param AuthorBookRepositoryInterface $authorBookRepository
     * @param AuthorInfoRepositoryInterface $authorInfoRepository
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(BookRepositoryInterface $bookRepository, AuthorBookRepositoryInterface $authorBookRepository, AuthorInfoRepositoryInterface $authorInfoRepository, CategoryRepositoryInterface $categoryRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->authorBookRepository = $authorBookRepository;
        $this->authorInfoRepository = $authorInfoRepository;
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function getBookForHomePage() {
        return view('index',['books'=>$this->bookRepository->getALl()]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function getBookForManageBookPage() {
        return view('admin.manage_book',['books'=>$this->bookRepository->getALl()]);
    }

    /**
     * @param $bookId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function getBookEdit($bookId) {
        $book = $this->bookRepository->find($bookId);
        $categoryOfBook = $this->categoryRepository->find($book->category_id);
        $categoryChildren = $this->categoryRepository->getCategoryChildByCategoryParentId($categoryOfBook->category_parent_id);
        return view('admin.edit_book',['book'=>$book,'categoryParents'=>$this->categoryRepository->getCategoryParent(),'categoryOfBook'=>$categoryOfBook,'categoryChildren'=>$categoryChildren]);
    }

    /**
     * @param $bookId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function getDetailBook($bookId) {
        $authorInfo = [];
        $authorBooks = $this->authorBookRepository->getAuthorBook($bookId);
        foreach ($authorBooks as $authorBook) {
            array_push($authorInfo,$this->authorInfoRepository->find($authorBook->id));
        }
        return view('detail_book',['book'=>$this->bookRepository->find($bookId),'authors' => $authorInfo]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function editBook(Request $request) {
        $book = $this->bookRepository->find($request->input('bookId'));
        $validation = Validator::make($request->all(),[
            'yearPublish'=> 'numeric | required',
            'priceRent' => 'numeric| required',
            'weight' => 'numeric| required',
            'totalPage' => 'numeric| required',
            'quantity' => 'numeric| required',
            'categoryChildren' => 'required',
            'bookImage' => 'required',
            'bookDescription' => 'required',
            'bookName' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }
        $book->name = $request->input('bookName');
        $book->thumbnail = $request->input('bookImage');
        $book->year_publish = $request->input('yearPublish');
        $book->price_rent = $request->input('priceRent');
        $book->weight = $request->input('weight');
        $book->total_page = $request->input('totalPage');
        $book->quantity = $request->input('quantity');
        $book->category_id = $request->input('categoryChildren');
        $book->description = $request->input('bookDescription');
        $this->bookRepository->update($book);
        return redirect(route('manageBook'));
    }
    public function addBookPage() {
        return view('admin.add_book',['categoryParents'=>$this->categoryRepository->getCategoryParent()]);
    }
    public function addBook(Request $request) {
        $book = new Book();
        $validation = Validator::make($request->all(),[
            'yearPublish'=> 'numeric | required',
            'priceRent' => 'numeric| required',
            'weight' => 'numeric| required',
            'totalPage' => 'numeric| required',
            'quantity' => 'numeric| required',
            'categoryChildren' => 'required',
            'bookImage' => 'required',
            'bookDescription' => 'required',
            'bookName' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }
        $book->name = $request->input('bookName');
        $book->thumbnail = $request->input('bookImage');
        $book->year_publish = $request->input('yearPublish');
        $book->price_rent = $request->input('priceRent');
        $book->weight = $request->input('weight');
        $book->total_page = $request->input('totalPage');
        $book->quantity = $request->input('quantity');
        $book->category_id = $request->input('categoryChildren');
        $book->description = $request->input('bookDescription');
        $book->status = 1;
        $this->bookRepository->add($book);
        return redirect(route('manageBook'));
    }
    public function lockBook($bookId) {
        $book = $this->bookRepository->find($bookId);
        $book->status = 0;
        $this->bookRepository->update($book);
        return redirect(route('manageBook'));
    }
    public function unlockBook($bookId) {
        $book = $this->bookRepository->find($bookId);
        $book->status = 1;
        $this->bookRepository->update($book);
        return redirect(route('manageBook'));
    }
    public function getBookByCategory($categoryId) {
        return view ('index',['books'=>$this->bookRepository->getBookByCategory($categoryId)]);
    }
}
