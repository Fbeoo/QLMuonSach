<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Repositories\AuthorBookRepositoryInterface;
use App\Repositories\AuthorInfoRepositoryInterface;
use App\Repositories\BookRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\Eloquent\BookRepository;
use http\Env\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;
use function Illuminate\Tests\Integration\Routing\fail;
use function Laravel\Prompts\error;

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

    /** ADMIN */

    public function getBookForManageBookPage() {
        try {
            $books = $this->bookRepository->getALl();

            return view('admin.manage_book',['books'=>$books]);
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
            $book = $this->bookRepository->find($request->input('id'));
            if (!$book) {
                return response()->json(['errors'=>'Không tìm thấy sách']);
            }
            $validation = Validator::make($request->all(),[
                'yearPublish'=> 'required|integer|min:1',
                'priceRent' => 'required|integer|min:1',
                'weight' => 'required|integer|min:1',
                'totalPage' => 'required|integer|min:1',
                'quantity' => 'required|integer|min:1',
                'categoryId' => 'required',
                'thumbnail' => 'required',
                'description' => 'required',
                'name' => 'required'
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

                    'categoryId.required' => @trans('message.categoryIdValidateRequired'),

                    'thumbnail.required' => @trans('message.thumbnailValidateRequired'),
//                'thumbnail.image' => 'File được đưa lên không phải định dạng ảnh',
//                'thumbnail.mimes' => 'Định dạng file ảnh không hợp lệ',
//                'thumbnail.max' => 'Kích thước tệp quá giới hạn',

                    'description.required' => @trans('message.descriptionValidateRequired'),

                    'name.required' => @trans('message.nameValidateRequired'),
                ]
            );
            if ($validation->fails()) {
                return response()->json(['errorValidate'=>$validation->errors()]);
            }
            $book->name = $request->input('name');
            $book->thumbnail = $request->input('thumbnail');
            $book->year_publish = $request->input('yearPublish');
            $book->price_rent = $request->input('priceRent');
            $book->weight = $request->input('weight');
            $book->total_page = $request->input('totalPage');
            $book->quantity = $request->input('quantity');
            $book->category_id = $request->input('categoryId');
            $book->description = $request->input('description');
            $this->bookRepository->update($book);

            $authorBook = $this->authorBookRepository->getAuthorBook($request->input('id'));
            $authorBook[0]->author_id = $request->input('authorId');
            $this->authorBookRepository->update($authorBook[0]);

            return response()->json(['success' => 'Sửa sách thành công']);
        }catch (\Exception $e) {
            throw new \Exception($e);
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
            $book = new Book();
            $validation = Validator::make($request->all(),[
                'yearPublish'=> 'required|integer|min:1',
                'priceRent' => 'required|integer|min:1',
                'weight' => 'required|integer|min:1',
                'totalPage' => 'required|integer|min:1',
                'quantity' => 'required|integer|min:1',
                'categoryId' => 'required',
                'thumbnail' => 'required|mimes:jpeg,png,jpg|max:2048',
                'description' => 'required',
                'name' => 'required',
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

                    'categoryId.required' => @trans('message.categoryIdValidateRequired'),

                    'thumbnail.required' => @trans('message.thumbnailValidateRequired'),
//                'thumbnail.image' => 'File được đưa lên không phải định dạng ảnh',
                'thumbnail.mimes' => 'Định dạng file ảnh không hợp lệ',
//                'thumbnail.max' => 'Kích thước tệp quá giới hạn',

                    'description.required' => @trans('message.descriptionValidateRequired'),

                    'name.required' => @trans('message.nameValidateRequired'),

                    'authorId.required' => @trans('message.authorIdValidateRequired')
                ]
            );
            if ($validation->fails()) {
                return response()->json(['errorValidate'=>$validation->errors()]);
            }
            $book->name = $request->input('name');
            $book->thumbnail = $request->input('thumbnail');
            $book->year_publish = $request->input('yearPublish');
            $book->price_rent = $request->input('priceRent');
            $book->weight = $request->input('weight');
            $book->total_page = $request->input('totalPage');
            $book->quantity = $request->input('quantity');
            $book->category_id = $request->input('categoryId');
            $book->description = $request->input('description');
            $book->status = Book::statusAvailable;
            $bookId = $this->bookRepository->add($book);

            $authorBook = new AuthorBook();
            $authorBook->book_id = $bookId;
            $authorBook->author_id = $request->input('authorId');
            $this->authorBookRepository->add($authorBook);

            return response()->json(['success' => 'Thêm sách thành công']);
        }catch (\Exception $e) {
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
                return \response()->json(['error'=>'Không tồn tại sách']);
            }
                $book->status = Book::statusLock;
            $this->bookRepository->update($book);

            return response()->json(['success' => 'Khóa sách thành công']);
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
                return response()->json(['error'=>'Không tồn tại sách']);
            }
            $book->status = Book::statusAvailable;
            $this->bookRepository->update($book);

            return response()->json(['message' => 'Mở khóa sách thành công']);
        }catch (\Exception $e) {
            return \response()->json(['error'=>$e]);
        }
    }

    /** USER */

    public function getBookForHomePage() {
        try {
            $books = $this->bookRepository->getALl();

            return view('index',['books'=>$books]);
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
            $authorInfo = [];
            $authorBooks = $this->authorBookRepository->getAuthorBook($bookId);
            foreach ($authorBooks as $authorBook) {
                array_push($authorInfo,$this->authorInfoRepository->find($authorBook->id));
            }

            return view('detail_book',[
                'book'=>$this->bookRepository->find($bookId),
                'authors' => $authorInfo
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
        $books = $this->bookRepository->getBookByCategory($categoryId);
        return view ('index',['books'=>$books]);
    }

}
