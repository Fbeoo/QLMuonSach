<?php

namespace App\Http\Controllers;

use App\Exports\ExportInvoiceRentBook;
use App\Http\Controllers\Controller;
use App\Imports\BookImport;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\DetailHistoryRentBook;
use App\Models\HistoryRentBook;
use App\Repositories\AuthorBookRepositoryInterface;
use App\Repositories\AuthorInfoRepositoryInterface;
use App\Repositories\BookRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\DetailHistoryRentBookRepositoryInterface;
use App\Repositories\Eloquent\BookRepository;
use App\Repositories\HistoryRentBookRepositoryInterface;
use AWS\CRT\Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use PHPUnit\Exception;
use function Illuminate\Tests\Integration\Routing\fail;
use function Laravel\Prompts\error;
use function Webmozart\Assert\Tests\StaticAnalysis\uuid;

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

    protected $detailHistoryRentBookRepository;

    protected $historyRentBookRepository;

    /**
     * @param BookRepositoryInterface $bookRepository
     * @param AuthorBookRepositoryInterface $authorBookRepository
     * @param AuthorInfoRepositoryInterface $authorInfoRepository
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        BookRepositoryInterface $bookRepository,
        AuthorBookRepositoryInterface $authorBookRepository,
        AuthorInfoRepositoryInterface $authorInfoRepository,
        CategoryRepositoryInterface $categoryRepository,
        DetailHistoryRentBookRepositoryInterface $detailHistoryRentBookRepository,
        HistoryRentBookRepositoryInterface $historyRentBookRepository
    )
    {
        $this->bookRepository = $bookRepository;
        $this->authorBookRepository = $authorBookRepository;
        $this->authorInfoRepository = $authorInfoRepository;
        $this->categoryRepository = $categoryRepository;
        $this->detailHistoryRentBookRepository = $detailHistoryRentBookRepository;
        $this->historyRentBookRepository = $historyRentBookRepository;
    }

    /** ADMIN */

    public function getBookForPagingInManageBookPage() {
        try {
            $books = $this->bookRepository->getBookWithAuthorAndCategory();
            return $books;
        }catch (\Exception $e) {
            return \response()->json(['error'=>$e]);
        }
    }
    public function getBookForManageBookPage() {
        try {
            $books = $this->bookRepository->getBookWithAuthorAndCategory();
            $currentPage = $books->currentPage();
            $totalPages = $books->lastPage();
            $categories = $this->categoryRepository->getCategoryParent();
            return view('admin.manage_book',[
                'books'=>$books,
                'categories' => $categories,
                'currentPage' => $currentPage,
                'totalPage' => $totalPages
            ]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param $bookId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     * @throws \Exception
     */
    public function getBookEdit($bookId) {
        try {
            $book = $this->bookRepository->find($bookId);
            if (!$book)
                return view ('error.404');
            $categoryOfBook = $this->categoryRepository->find($book->category_id);
            $categoryChildren = $this->categoryRepository->getCategoryChildByCategoryParentId($categoryOfBook->category_parent_id);
            $authorOfBook = $this->authorBookRepository->getAuthorBook($bookId);
            $authors = $this->authorInfoRepository->getALl();
            $categoryParents = $this->categoryRepository->getCategoryParent();

            return view('admin.edit_book',[
                'book'=>$book,
                'categoryParents'=>$categoryParents,
                'categoryOfBook'=>$categoryOfBook,
                'categoryChildren'=>$categoryChildren,
                'authorOfBook'=>$authorOfBook,
                'authors' => $authors
            ]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function editBook(Request $request) {
        try {
            DB::beginTransaction();
            $book = $this->bookRepository->find($request->input('bookId'));
            if (!$book) {
                return response()->json(['error'=>'Không tìm thấy sách']);
            }
                $validation = Validator::make($request->all(),[
                    'yearPublish'=> 'required|integer|min:1',
                    'priceRent' => 'required|integer|min:1',
                    'weight' => 'required|integer|min:1',
                    'totalPage' => 'required|integer|min:1',
                    'quantity' => 'required|integer|min:1',
                    'categoryChildren' => 'required',
                    'description' => 'required',
                    'thumbnail' => 'mimes:jpeg,png,jpg|max:4096',
                    'bookName' => 'required',
                    'authorId' => 'required'
                ],[
                    'yearPublish.required' => @trans('message.yearPublishValidateRequired'),
                    'yearPublish.integer' => @trans('message.yearPublishValidateInteger'),
                    'yearPublish.min' => @trans('message.yearPublishValidateMin'),

                    'priceRent.required' => @trans('message.priceRentValidateRequired'),
                    'priceRent.integer' => @trans('message.priceRentValidateInteger'),
                    'priceRent.min' => @trans('message.priceRentValidateMin'),


                    'weight.required' => @trans('message.weightValidateRequired'),
                    'weight.integer' => @trans('message.weightValidateInteger'),
                    'weight.min' => @trans('message.weightValidateMin'),

                    'totalPage.required' => @trans('message.totalPageValidateRequired'),
                    'totalPage.integer' => @trans('message.totalPageValidateInteger'),
                    'totalPage.min' => @trans('message.totalPageValidateMin'),

                    'quantity.required' => @trans('message.quantityValidateRequired'),
                    'quantity.integer' => @trans('message.quantityValidateInteger'),
                    'quantity.min' => @trans('message.quantityValidateMin'),

                    'categoryChildren.required' => @trans('message.categoryChildrenValidateRequired'),

                    'thumbnail.required' => @trans('message.thumbnailValidateRequired'),
                    'thumbnail.mimes' => @trans('message.thumbnailValidateMimes'),
                    'thumbnail.max' => @trans('message.thumbnailValidateMax'),

                    'description.required' => @trans('message.descriptionValidateRequired'),

                    'bookName.required' => @trans('message.bookNameValidateRequired'),

                    'authorId.required' => @trans('message.authorIdValidateRequired'),
                ]
            );
            if ($validation->fails()) {
                return response()->json(['errorValidate'=>$validation->errors()]);
            }
            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('public/img');
                $book->thumbnail = Str::after($path,'/');
            }

            $categoryChildren = $this->categoryRepository->find($request->input('categoryChildren'));
            if (!$categoryChildren) {
                return response()->json(['error'=>@trans('message.categoryNotAvailable')]);
            }

            $author = $this->authorInfoRepository->find($request->input('authorId'));
            if (!$author) {
                return response()->json(['error'=>@trans('message.authorNotAvailable')]);
            }

            $book->name = $request->input('bookName');
            $book->year_publish = $request->input('yearPublish');
            $book->price_rent = $request->input('priceRent');
            $book->weight = $request->input('weight');
            $book->total_page = $request->input('totalPage');
            $book->quantity = $request->input('quantity');
            $book->description = $request->input('description');
            $book->category_id = $request->input('categoryChildren');
            $this->bookRepository->update($book);

            $authorBook = $this->authorBookRepository->getAuthorBook($request->input('bookId'));
            $authorBook[0]->author_id = $request->input('authorId');
            $this->authorBookRepository->update($authorBook[0]);
            DB::commit();

            return response()->json(['success' => @trans('message.updateBookSuccessfully')]);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error',$e]);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function addBookPage() {
        $categoryParents = $this->categoryRepository->getCategoryParent();
        $authors = $this->authorInfoRepository->getALl();

        return view('admin.add_book',[
            'categoryParents'=>$categoryParents,
            'authors'=>$authors
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBook(Request $request) {
        try {
            DB::beginTransaction();
            $book = new Book();
            $validation = Validator::make($request->all(),[
                'yearPublish'=> 'required|integer|min:1',
                'priceRent' => 'required|integer|min:1',
                'weight' => 'required|integer|min:1',
                'totalPage' => 'required|integer|min:1',
                'quantity' => 'required|integer|min:1',
                'categoryChildren' => 'required',
                'thumbnail' => 'required|mimes:jpeg,png,jpg|max:4096',
                'description' => 'required',
                'bookName' => 'required',
                'authorId' => 'required'
            ],[
                    'yearPublish.required' => @trans('message.yearPublishValidateRequired'),
                    'yearPublish.integer' => @trans('message.yearPublishValidateInteger'),
                    'yearPublish.min' => @trans('message.yearPublishValidateMin'),

                    'priceRent.required' => @trans('message.priceRentValidateRequired'),
                    'priceRent.integer' => @trans('message.priceRentValidateInteger'),
                    'priceRent.min' => @trans('message.priceRentValidateMin'),


                    'weight.required' => @trans('message.weightValidateRequired'),
                    'weight.integer' => @trans('message.weightValidateInteger'),
                    'weight.min' => @trans('message.weightValidateMin'),

                    'totalPage.required' => @trans('message.totalPageValidateRequired'),
                    'totalPage.integer' => @trans('message.totalPageValidateInteger'),
                    'totalPage.min' => @trans('message.totalPageValidateMin'),

                    'quantity.required' => @trans('message.quantityValidateRequired'),
                    'quantity.integer' => @trans('message.quantityValidateInteger'),
                    'quantity.min' => @trans('message.quantityValidateMin'),

                    'categoryChildren.required' => @trans('message.categoryChildrenValidateRequired'),

                    'thumbnail.required' => @trans('message.thumbnailValidateRequired'),
                    'thumbnail.mimes' => @trans('message.thumbnailValidateMimes'),
                    'thumbnail.max' => @trans('message.thumbnailValidateMax'),

                    'description.required' => @trans('message.descriptionValidateRequired'),

                    'bookName.required' => @trans('message.bookNameValidateRequired'),

                    'authorId.required' => @trans('message.authorIdValidateRequired')
                ]
            );
            if ($validation->fails()) {
                return response()->json(['errorValidate'=>$validation->errors()]);
            }

            $categoryChildren = $this->categoryRepository->find($request->input('categoryChildren'));
            if (!$categoryChildren) {
                return response()->json(['error'=>@trans('categoryNotAvailable')]);
            }

            $author = $this->authorInfoRepository->find($request->input('authorId'));
            if (!$author) {
                return response()->json(['error'=>@trans('authorNotAvailable')]);
            }

            $path = $request->file('thumbnail')->store('public/img');

            $book->name = $request->input('bookName');
            $book->thumbnail = Str::after($path,'/');
            $book->year_publish = $request->input('yearPublish');
            $book->price_rent = $request->input('priceRent');
            $book->weight = $request->input('weight');
            $book->total_page = $request->input('totalPage');
            $book->quantity = $request->input('quantity');
            $book->category_id = $request->input('categoryChildren');
            $book->description = $request->input('description');
            $book->status = Book::statusAvailable;
            $bookId = $this->bookRepository->add($book);

            $authorBook = new AuthorBook();
            $authorBook->book_id = $bookId->id;
            $authorBook->author_id = $request->input('authorId');
            $this->authorBookRepository->add($authorBook);
            DB::commit();

            return response()->json(['success' => @trans('message.addBookSuccessfully')]);
        }catch (\Exception $e) {
            DB::rollBack();
            return \response()->json(['error'=>$e]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lockBook(Request $request) {
        try {
            $book = $this->bookRepository->find($request->input('id'));
            if (!$book) {
                return \response()->json(['error'=>@trans('message.bookNotAvailable')]);
            }
                $book->status = Book::statusLock;
            $this->bookRepository->update($book);

            return response()->json(['success' => @trans('message.lockBookSuccessfully')]);
        }catch (\Exception $e) {
            return \response()->json(['error'=>$e]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlockBook(Request $request) {
        try {
            $book = $this->bookRepository->find($request->input('id'));
            if (!$book) {
                return response()->json(['error'=>@trans('message.bookNotAvailable')]);
            }
            $book->status = Book::statusAvailable;
            $this->bookRepository->update($book);

            return response()->json(['message' => @trans('message.unlockBookSuccessfully')]);
        }catch (\Exception $e) {
            return \response()->json(['error'=>$e]);
        }
    }

    public function getBookByName($bookName) {
        try {
            $books = $this->bookRepository->getBookByName($bookName);
            $currentPage = $books->currentPage();
            $totalPages = $books->lastPage();
            return [
                'books' => $books,
                'currentPage' => $currentPage,
                'totalPage' => $totalPages
            ];
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function sortBookByYearPublish($type) {
        try {
            $resultSortBooks = $this->bookRepository->sortBookByYearPublish(Session::get('books'),$type);
            return $resultSortBooks;
        }catch (\Exception $e) {
            return \response()->json(['error'=>$e]);
        }
    }

    public function filterBookByStatus ($type) {
        try {
            $resultFilter = $this->bookRepository->filterBookByStatus(Session::get('books'),$type);
            return new ResourceCollection($resultFilter);
        }catch (\Exception $e) {
            return \response()->json(['error'=>$e]);
        }
    }

    public function filterBookByCategoryParent ($categoryParentId) {
        try {
            $books = $this->bookRepository->getBookByCategoryParent($categoryParentId);
            return $books;
        }catch (\Exception $e) {
            return \response()->json(['error'=>$e]);
        }
    }

    public function filterBookByCategoryChildren($categoryChildrenId) {
        try {
            $books = $this->bookRepository->getBookByCategory($categoryChildrenId);
            return $books;
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function filterBookByRangeOfYear(Request $request) {
        try {
            $validation = Validator::make($request->all(), [
                'minYear' => 'required|min:1|integer',
                'maxYear' => 'required|min:1|integer'
            ]);
            if ($request->input('minYear')>$request->input('maxYear')) {
                $validation->errors()->add('minYear',@trans('message.filterRangeYearValidateError'));
            }
            if ($validation->fails()) {
                return response()->json(['errorValidate'=>$validation->errors()]);
            }
            $resultFilter = $this->bookRepository->filterBookByRangeOfYear(Session::get('books'),$request->input('minYear'),$request->input('maxYear'));
            return $resultFilter;
        }catch (\Exception $e) {
            return \response()->json(['error'=>$e]);
        }
    }

    public function getBookByRangeOfYear($minYear,$maxYear) {
        try {
            $resultFilter = $this->bookRepository->filterBookByRangeOfYear(Session::get('books'),$minYear,$maxYear);
            return $resultFilter;
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function filterBook (Request $request) {
        try {
            $books = $this->bookRepository->filterBook($request->all());
            return \response()->json($books);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    /** USER */

    public function getBookForHomePage() {
        try {
            $books = $this->bookRepository->getBookForHomePage();
            $authors = $this->authorInfoRepository->getAuthorForHomePage();
            return view('index',[
                'books'=>$books,
                'authors' => $authors
            ]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param $bookId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     * @throws \Exception
     */
    public function getDetailBook($bookId) {
        try {
            $books = $this->bookRepository->getDetailBook($bookId);
            $detailHistoryRentBook = $this->detailHistoryRentBookRepository->getNumberOfBookRenting($bookId);
            $numberBookAvailable = $books->quantity;
            foreach ($detailHistoryRentBook as $detail) {
                $numberBookAvailable -= $detail->quantity;
            }
            return view('detail_book',[
                'book'=>$books,
                'numberBookAvailable' => $numberBookAvailable,
            ]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param $categoryId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function getBookByCategory($categoryId) {
        $books = $this->bookRepository->getBookByCategoryForUser($categoryId);
        return view ('allBook',['books'=>$books]);
    }

    public function showBookInAllBookPage() {
        try {
            $books = $this->bookRepository->getBookForAllBookPage();
            return view('allBook',['books'=>$books]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function addBookToCart(Request $request) {
        try {
//            dd(Session::get('cart'));
            $book = $this->bookRepository->find($request->input('bookId'));
            $detailHistoryRentBook = $this->detailHistoryRentBookRepository->getNumberOfBookRenting($request->input('bookId'));
            $numberBookAvailable = $book->quantity;
            foreach ($detailHistoryRentBook as $detail) {
                $numberBookAvailable -= $detail->quantity;
            }
            if (!Session::get('cart')) {
                $collection = new Collection();
                $collectionChild = new Collection();
                $arr = [
                    'book' => $book,
                    'line' => 1,
                    'quantityLine' => 1,
                    'linePrice' => $book->price_rent,
                    'quantityAvailable' => $numberBookAvailable
                ];
                $collectionChild->push($arr);
                $totalPrice = $book->price_rent;
                $collection->put('bookInCart',$collectionChild);
                $collection->put('totalPrice',$totalPrice);
                $currentDate = now()->format('d/m/Y');
                $collection->put('dateRent',$currentDate);
                $collection->put('dateExpire',$currentDate);
                $collection->put('numberDayRent',1);
                $collection->put('totalBookInCart',1);
                Session::put('cart',$collection);
                return \response()->json(['success'=>@trans('message.addBookToCartSuccessfully'),'cart'=>$collection]);
            }
            $collection = Session::get('cart');
            $collectionChild = $collection->get('bookInCart');
            foreach ($collectionChild as $bookInCart) {
                if ($bookInCart['book']->id === $book->id) {
                    $collectionChild->transform(function ($item) use ($book) {
                        if ($item['book']->id === $book->id) {
                            $item['quantityLine'] += 1;
                            $item['linePrice'] += $book->price_rent;
                        }
                        return $item;
                    });
                    $totalPrice = $collection->get('totalPrice') + $book->price_rent;
                    $collection->put('totalPrice',$totalPrice);
                    $collection->put('bookInCart',$collectionChild);
                    $totalBookInCart = $collection->get('totalBookInCart');
                    $totalBookInCart += 1;
                    $collection->put('totalBookInCart',$totalBookInCart);
                    Session::put('cart',$collection);
                    return \response()->json(['success'=>@trans('message.addBookToCartSuccessfully'),'cart'=>$collection]);
                }
            }

            $arr = [
                'book' => $book,
                'line' => $collectionChild->count() + 1,
                'quantityLine' => 1,
                'linePrice' => $book->price_rent,
                'quantityAvailable' => $numberBookAvailable
            ];
            $collectionChild->push($arr);
            $totalPrice = $collection->get('totalPrice') + $book->price_rent;
            $collection->put('totalPrice',$totalPrice);
            $totalBookInCart = $collection->get('totalBookInCart');
            $totalBookInCart += 1;
            $collection->put('totalBookInCart',$totalBookInCart);
            Session::put('cart',$collection);
            return \response()->json(['success'=>@trans('message.addBookToCartSuccessfully'),'cart'=>$collection]);
        }catch (\Exception $e) {
            return \response()->json(['error'=>$e]);
        }
    }

    public function showBookInCart() {
        try {
//            Session::forget('cart');
            $cart = Session::get('cart');
            return view('cart',['cart'=>$cart]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }


    public function rentSingleBook(Request $request) {
        try {
            DB::beginTransaction();
//            $validation = Validator::make($request->all(),[
//                'dateRent' => 'required|date_format:d/m/Y|after:tomorrow',
//                'quantityRent' => 'required|integer|min:1'
//            ],[
//                'dateRent.required' => 'Ngày thuê không được để trống',
//                'dateRent.date_format' => 'Định dạng ngày không đúng',
//                'dateRent.after' => 'Ngày thuê tối thiểu phải là ngày mai',
//
//                'quantityRent.required' => 'Số lượng thuê không được để trống',
//                'quantityRent.integer' => 'Số lượng thuê phải là số nguyên',
//                'quantityRent.min' => 'Số lượng thuê phải lớn hơn 0'
//
//            ]);
//
//            if (intval($request->input('numberBookAvailable'))<intval($request->input('quantityRent'))) {
//                $validation->errors()->add('quantityRent','Số sách mượn vượt quá số lượng đang có trong kho');
//            }
//            if ($validation->fails()) {
//                return \response()->json(['errorValidate'=>$validation->errors()]);
//            }


            $historyRentBook = new HistoryRentBook();

            $dateRent = Carbon::createFromFormat('d/m/Y', $request->input('dateExpire'));

            $historyRentBook->rent_date = now()->format('Y/m/d');
            $historyRentBook->status = HistoryRentBook::statusPending;
            $historyRentBook->expiration_date = $dateRent->format('Y/m/d');
            $historyRentBook->total_price =$request->input('totalPrice');
            $historyRentBook->user_id = session()->get('user')->id;
            $historyRentBookId = $this->historyRentBookRepository->add($historyRentBook);

            $detailHistoryRentBook = new DetailHistoryRentBook();
            $detailHistoryRentBook->quantity = $request->input('quantityRent');
            $detailHistoryRentBook->book_id = $request->input('bookId');
            $detailHistoryRentBook->history_rent_book_id = $historyRentBookId->id;
            $this->detailHistoryRentBookRepository->add($detailHistoryRentBook);

            DB::commit();
            return \response()->json(['success'=>@trans('message.rentBookSuccessfully')]);
        }catch (\Exception $e) {
            DB::rollBack();
            return \response()->json(['error'=>$e]);
        }
    }

    public function rentMultiBook(Request $request) {
        try {
            DB::beginTransaction();
//            $validation = Validator::make($request->all(), [
//                'dateRent' => 'required|date_format:d/m/Y|after:tomorrow'
//            ],[
//                'dateRent.required' => 'Ngày mượn không được để trống',
//                'dateRent.date_format' => 'Sai định dạng ngày',
//                'dateRent.after' => 'Ngày mượn tối thiểu phải là ngày mai'
//            ]);
//
//            if ($validation->fails()) {
//                return \response()->json(['error'=>$validation->errors()]);
//            }

            $cart = Session::get('cart');

            $dateRent = Carbon::createFromFormat('d/m/Y', $request->input('dateRent'));

            $historyRentBook = new HistoryRentBook();
            $historyRentBook->rent_date = now()->format('Y/m/d');
            $historyRentBook->status = HistoryRentBook::statusPending;
            $historyRentBook->expiration_date = $dateRent->format('Y/m/d');
            $historyRentBook->total_price = $cart->get('totalPrice');
            $historyRentBook->user_id = session()->get('user')->id;
            $historyRentBookId = $this->historyRentBookRepository->add($historyRentBook);

            $bookInCart = $cart->get('bookInCart');
            foreach ($bookInCart as $book) {
                $detailHistoryRentBook = new DetailHistoryRentBook();
                $detailHistoryRentBook->book_id = $book['book']->id;
                $detailHistoryRentBook->history_rent_book_id = $historyRentBookId->id;
                $detailHistoryRentBook->quantity = $book['quantityLine'];
                $this->detailHistoryRentBookRepository->add($detailHistoryRentBook);
            }
            DB::commit();
            Session::forget('cart');

            return \response()->json(['success'=>@trans('message.rentBookSuccessfully')]);
        }catch (\Exception $e) {
            DB::rollBack();
            return \response()->json(['error'=>$e]);
        }
    }

    public function changeNumberBookInCart(Request $request) {
        try {
            $cart = Session::get('cart');
            $bookInCart = $cart->get('bookInCart');
            $bookInCart->transform(function ($item) use ($request,$cart) {
                if ($item['line'] == $request->input('line')) {
                    $item['quantityLine'] = $request->input('quantity');
                    $item['linePrice'] = $item['book']->price_rent * $request->input('quantity')*$cart->get('numberDayRent');
                }
                return $item;
            });
            $cart->put('bookInCart',$bookInCart);
            $totalPrice = 0;
            $totalBookInCart = 0;
            foreach ($bookInCart as $book) {
                $totalPrice += $book['linePrice'];
                $totalBookInCart += $book['quantityLine'];
            }
            $cart->put('totalPrice',$totalPrice);
            $cart->put('totalBookInCart',$totalBookInCart);
            Session::put('cart',$cart);
            return $cart;
        }catch (\Exception $e) {
            return \response()->json(['error'=>$e]);
        }
    }

    public function changeDateRent(Request $request) {
        try {
            $validation = Validator::make($request->all(), [
                'dateRent' => 'required|date_format:d/m/Y|after:tomorrow'
            ],[
                'dateRent.required' => @trans('message.dateRentRequired'),
                'dateRent.date_format' => @trans('message.dateRentDateFormat'),
                'dateRent.after' => @trans('message.dateRentAfter')
            ]);

            if ($validation->fails()) {
                return \response()->json(['errorValidate'=>$validation->errors()]);
            }

            $currentDate = Carbon::now()->startOfDay();
            $dateRent = Carbon::createFromFormat('d/m/Y', $request->input('dateRent'));
            $numberDayRent = $currentDate->diffInDays($dateRent);

            $totalPrice = 0;

            $cart = Session::get('cart');

            $cart->put('dateExpire',$dateRent->format('d/m/Y'));

            $bookInCart = $cart->get('bookInCart');

            $bookInCart = $bookInCart->map(function ($item) use($numberDayRent) {
                $item['linePrice'] = $item['quantityLine']*$item['book']->price_rent*$numberDayRent;
                return $item;
            });

            foreach($bookInCart as $book) {
                $totalPrice += $book['book']->price_rent*$book['quantityLine']*$numberDayRent;
            }

            $cart->put('dateRent',now()->format('d/m/Y'));

            $cart->put('totalPrice',$totalPrice);

            $cart->put('bookInCart',$bookInCart);

            $cart->put('numberDayRent',$numberDayRent);

            Session::put('cart',$cart);

            return $cart;
        }catch (\Exception $e) {
            return \response()->json(['error'=>$e]);
        }
    }

    public function removeBookInCart(Request $request) {
        try {
            $cart = Session::get('cart');
            $bookInCart = $cart->get('bookInCart');
            $book = $bookInCart->first(function ($item) use($request) {
                return $item['line'] == $request->input('line');
            });
            $priceRemove = $book['linePrice'];
            $quantityBookRemove = $book['quantityLine'];
            $bookInCart = $bookInCart->reject (function ($item) use ($request) {
                return $item['line'] == $request->input('line');
            })->values();
            $bookInCart->transform(function ($item) use ($request) {
                if ($item['line'] > $request->input('line')) {
                    $item['line'] -= 1;
                }
                return $item;
            });
            $cart->put('bookInCart',$bookInCart);
            $totalPrice = $cart->get('totalPrice')-$priceRemove;
            $cart->put('totalPrice',$totalPrice);
            $totalBookInCart = $cart->get('totalBookInCart')-$quantityBookRemove;
            $cart->put('totalBookInCart',$totalBookInCart);
            Session::put('cart',$cart);
            return $cart;
        }catch (\Exception $e) {
            return \response()->json(['error'=>$e]);
        }
    }

    public function confirmRentBook(Request $request) {
        try {
            if ($request->input('typeRent') === 'rentSingleBook') {
//                $validation = Validator::make($request->all(),[
//                    'dateRent' => 'required|date_format:d/m/Y|after:tomorrow',
//                    'quantityRent' => 'required|integer|min:1'
//                ],[
//                    'dateRent.required' => 'Ngày thuê không được để trống',
//                    'dateRent.date_format' => 'Định dạng ngày không đúng',
//                    'dateRent.after' => 'Ngày thuê tối thiểu phải là ngày mai',
//
//                    'quantityRent.required' => 'Số lượng thuê không được để trống',
//                    'quantityRent.integer' => 'Số lượng thuê phải là số nguyên',
//       .
//
//             'quantityRent.min' => 'Số lượng thuê phải lớn hơn 0'
//
//                ]);
//
//                if (intval($request->input('numberBookAvailable'))<intval($request->input('quantityRent'))) {
//                    $validation->errors()->add('quantityRent','Số sách mượn vượt quá số lượng đang có trong kho');
//                }
//                if ($validation->fails()) {
//                    return \response()->json(['errorValidate'=>$validation->errors()]);
//                }

                $typeRent = $request->input('typeRent');

                $currentDate = Carbon::now()->startOfDay();
                $dateRent = Carbon::createFromFormat('d/m/Y', $request->input('dateRent'));
                $numberDayRent = $currentDate->diffInDays($dateRent);

                $book = $this->bookRepository->find($request->input('bookId'));

                $quantityRent = $request->input('quantityRent');

                $totalPrice = $book->price_rent*$numberDayRent*$quantityRent;

                return view('requestConfirmation',[
                    'book'=>$book,
                    'quantityRent'=>$quantityRent,
                    'totalPrice'=>$totalPrice,
                    'dateExpire'=>$dateRent->format('d/m/Y'),
                    'dateRent' => $currentDate->format('d/m/Y'),
                    'typeRent' => $typeRent
                ]);
            }
            else if ($request->input('typeRent') === 'rentMultiBook') {
//                $validation = Validator::make($request->all(), [
//                    'dateRent' => 'required|date_format:d/m/Y|after:tomorrow'
//                ],[
//                    'dateRent.required' => 'Ngày mượn không được để trống',
//                    'dateRent.date_format' => 'Sai định dạng ngày',
//                    'dateRent.after' => 'Ngày mượn tối thiểu phải là ngày mai'
//                ]);
//
//                if ($validation->fails()) {
//                    return \response()->json(['error'=>$validation->errors()]);
//                }

                $cart = Session::get('cart');
                $typeRent = $request->input('typeRent');
                return view('requestConfirmation',[
                    'cart' => $cart,
                    'dateRent' => $cart->get('dateRent'),
                    'dateExpire' => $cart->get('dateExpire'),
                    'typeRent' => $typeRent
                ]);
            }
            else {
                return view('error.404');
            }
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function validateRentSingleBook(Request $request) {
        try {
            $validation = Validator::make($request->all(),[
                'dateRent' => 'required|date_format:d/m/Y|after:tomorrow',
                'quantityRent' => 'required|integer|min:1'
            ],[
                'dateRent.required' => @trans('message.dateRentRequired'),
                'dateRent.date_format' => @trans('message.dateRentDateFormat'),
                'dateRent.after' => @trans('message.dateRentAfter'),

                'quantityRent.required' => @trans('message.quantityRentRequired'),
                'quantityRent.integer' => @trans('message.quantityRentInteger'),
                'quantityRent.min' => @trans('message.quantityRentMin')

            ]);

            if (intval($request->input('numberBookAvailable'))<intval($request->input('quantityRent'))) {
                $validation->errors()->add('quantityRent',@trans('message.quantityRentMoreThanBookAvailable'));
            }
            if (count($validation->errors()) > 0) {
                return \response()->json(['errorValidate'=>$validation->errors()]);
            }
            return \response()->json(['success'=>@trans('message.validateSuccessfully')]);
        }catch (\Exception $e) {
            return \response()->json(['error'=>$e]);
        }
    }

    public function validateRentMultiBook(Request $request) {
        try {
            $cart = Session::get('cart');
            $validation = Validator::make($request->all(), [
                'dateRent' => 'required|date_format:d/m/Y|after:tomorrow'
            ],[
                'dateRent.required' => @trans('message.dateRentRequired'),
                'dateRent.date_format' => @trans('message.dateRentDateFormat'),
                'dateRent.after' => @trans('message.dateRentAfter')
            ]);

            $bookInCart = $cart->get('bookInCart');
            foreach ($bookInCart as $book) {
                if ($book['quantityAvailable'] < $book['quantityLine']) {
                    $validation->errors()->add('quantityRent'.$book['line'],@trans('message.quantityRentMoreThanBookAvailable'));
                }
            }

            if (count($validation->errors()) > 0) {
                return \response()->json(['errorValidate'=>$validation->errors()]);
            }

            return \response()->json(['success'=>@trans('message.validateSuccessfully')]);
        }catch (\Exception $e) {
            return \response()->json(['error'=>$e]);
        }
    }

    public function searchBook(Request $request) {
        try {
            $resultSearch = $this->bookRepository->searchBook($request->input('bookNameSearch'));
            return view('searchPage',['books'=>$resultSearch]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function countBookInCart() {
        try {
            if (\session()->has('cart')) {
                $cart = \session()->get('cart');
                $totalBookInCart = $cart['totalBookInCart'];
                return $totalBookInCart;
            }
            else {
                return 0;
            }
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function addBookByExcelFile(Request $request) {
        try {
            $validation = Validator::make($request->all(),[
                'nameFileImport' => 'required|mimes:xlsx|max:4096'
            ],[
                'nameFileImport.required' => 'File không được để trống',
                'nameFileImport.mimes' => 'Phần mở rộng của file phải là xlsx',
                'nameFileImport.max' => 'Dung lượng file không được quá 4MB'
            ]);

            if ($validation->fails()) {
                return response()->json(['errorValidate' => $validation->errors()]);
            }
            Excel::import(new BookImport($this->categoryRepository,$this->authorInfoRepository,$this->authorBookRepository,$this->bookRepository), $request->file('nameFileImport'));
            return response()->json(['success' => 'Thêm sách thành công']);
        }catch (ValidationException $e) {
            return response()->json(['errorValidateExcel' => $e->getMessage()]);
        }
    }

    public function sortBook(Request $request) {
        try {
            if ($request->input('sortBook') === 'priceAsc') {
                $books = Session::get('bookAsc');
            }
            else if ($request->input('sortBook') === 'priceDesc') {
                $books = Session::get('bookDesc');
            }
            else if ($request->input('sortBook' === 'default')) {
                $books = Session::get('bookDefault');
            }
            else {
                return view('error.404');
            }
            return view('allBook',['books' => $books]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
