<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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


    public function editBook(Request $request) {
        $book = $this->bookRepository->find($request->input('id'));
        $book->name = $request->input('name');
        $book->thumbnail = $request->input('thumbnail');
        $book->year_publish = $request->input('year_publish');
        $book->price_rent = $request->input('price_rent');
        $book->weight = $request->input('weight');
        $book->total_page = $request->input('total_page');
        $book->quantity = $request->input('quantity');
        $book->category_id = $request->input('category_id');
        $book->description = $request->input('description');
        $this->bookRepository->update($book);
        return response()->json(['message' => 'Sửa sách thành công']);
    }
    public function addBookPage() {
        return view('admin.add_book',['categoryParents'=>$this->categoryRepository->getCategoryParent()]);
    }
    public function addBook(Request $request) {
        $book = new Book();
        $book->name = $request->input('name');
        $book->thumbnail = $request->input('thumbnail');
        $book->year_publish = $request->input('year_publish');
        $book->price_rent = $request->input('price_rent');
        $book->weight = $request->input('weight');
        $book->total_page = $request->input('total_page');
        $book->quantity = $request->input('quantity');
        $book->category_id = $request->input('category_id');
        $book->description = $request->input('description');
        $book->status = 1;
        $this->bookRepository->add($book);
        return response()->json(['message' => 'Thêm sách thành công']);
    }
    public function lockBook(Request $request) {
        $book = $this->bookRepository->find($request->input('id'));
        $book->status = 0;
        $this->bookRepository->update($book);
        return response()->json(['message' => 'Khóa sách thành công']);
    }
    public function unlockBook(Request $request) {
        $book = $this->bookRepository->find($request->input('id'));
        $book->status = 1;
        $this->bookRepository->update($book);
        return response()->json(['message' => 'Mở khóa sách thành công']);
    }
    public function getBookByCategory($categoryId) {
        return view ('index',['books'=>$this->bookRepository->getBookByCategory($categoryId)]);
    }
    public function validateInput(Request $request) {
        $validation = Validator::make($request->all(),[
            'year_publish'=> 'required | integer | min:1',
            'price_rent' => 'required | integer | min:1',
            'weight' => 'required | integer | min:1',
            'total_page' => 'required | integer | min:1',
            'quantity' => 'required | integer | min:1',
            'category_id' => 'required',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required',
            'name' => 'required'
        ],[
                'year_publish.required' => 'Năm phát hành không được để trống',
                'year_publish.integer' => 'Năm phát hành phải là số nguyên',
                'year_publish.min' => 'Năm phát hành phải lớn hơn 0',

                'price_rent.required' => 'Giá thuê không được để trống',
                'price_rent.integer' => 'Giá thuê phải là số nguyên',
                'price_rent.min' => 'giá thuê phải lớn hơn 0',


                'weight.required' => 'Trọng lượng không được để trống',
                'weight.integer' => 'Trọng lượng phải là số nguyên',
                'weight.min' => 'Trọng lượng phải lớn hơn 0',

                'total_page.required' => 'Tổng số trang không được để trống',
                'total_page.integer' => 'Tổng số trang phải là số nguyên',
                'total_page.min' => 'Tổng số trang phải lớn hơn 0',

                'quantity.required' => 'Số lượng không được để trống',
                'quantity.integer' => 'Số lượng phải là số nguyên',
                'quantity.min' => 'Số lượng phải lớn hơn 0',

                'category_id.required' => 'Danh mục không được để trống',

                'thumbnail.required' => 'Ảnh của sách không được để trống',
                'thumbnail.image' => 'File được đưa lên không phải định dạng ảnh',
                'thumbnail.mimes' => 'Định dạng file ảnh không hợp lệ',
                'thumbnail.max' => 'Kích thước tệp quá giới hạn',

                'description.required' => 'Mô tả sách không được để trống',

                'name.required' => 'Tên của sách không được để trống'
            ]
        );
        if ($validation->fails()) {
            $error = [
                'yearPublishError' => $validation->errors()->first('year_publish'),
                'priceRentError' => $validation->errors()->first('price_rent'),
                'weightError' => $validation->errors()->first('weight'),
                'totalPageError' => $validation->errors()->first('total_page'),
                'quantityError' => $validation->errors()->first('quantity'),
                'categoryError' => $validation->errors()->first('category_id'),
                'thumbnailError' => $validation->errors()->first('thumbnail'),
                'descriptionError' => $validation->errors()->first('description'),
                'nameError' => $validation->errors()->first('name')
            ];
            return response()->json(['errors'=>$error]);
        }
        return response()->json(['success'=>'Dữ liệu hợp lệ']);
    }
}
