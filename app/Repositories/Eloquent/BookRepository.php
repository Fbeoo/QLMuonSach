<?php
namespace App\Repositories\Eloquent;
use App\Models\Book;
use App\Repositories\BookRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use PharIo\Version\Exception;

/**
 *
 */
class BookRepository extends BaseRepository implements BookRepositoryInterface {
    /**
     * @return mixed
     * @throws \Exception
     */
    public function getBookForPaging() {
        try {
            return $this->model->paginate(4);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param $categoryId
     * @return mixed
     * @throws \Exception
     */
    public function getBookByCategory($categoryId)
    {
        try {
            $books = $this->model
                ->where('category_id',$categoryId)
                ->with('category','authorBook','authorBook.authorInfo')
                ->paginate(10);
            Session::put('books',$this->model
                ->where('category_id',$categoryId)
                ->with('category','authorBook','authorBook.authorInfo')
                ->get());
            return $books;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getBookByName($bookName)
    {
        try {
            Session::put('books',$this->model::
                where('name','like','%'.$bookName.'%')
                ->with('category','authorBook','authorBook.authorInfo')
                ->get());
            return $this->model::
                where('name','like','%'.$bookName.'%')
                ->with('category','authorBook','authorBook.authorInfo')
                ->paginate(10);
        }catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
    }



    public function sortBookByYearPublish(Collection $books, $type)
    {
        try {
            $itemsPerPage = 10;
            $totalItems = $books->count();
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            if ($type == "desc") {
                $books = $books->sortByDesc('year_publish');
                $resultSort = new LengthAwarePaginator($books
                    ->forPage($currentPage, $itemsPerPage), $totalItems, $itemsPerPage, $currentPage);
                Session::put('books',$books);
                return new ResourceCollection($resultSort);
            } else if ($type == "asc") {
                $books = $books->sortBy('year_publish');
                $resultSort = new LengthAwarePaginator($books
                    ->forPage($currentPage, $itemsPerPage), $totalItems, $itemsPerPage, $currentPage);
                Session::put('books',$books);
                return new ResourceCollection($resultSort);
            }
        } catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function filterBookByStatus(Collection $books,$type)
    {
        try {
            Session::put('books',$books);
            $itemsPerPage = 10;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            if ($type == 'available') {
                $resultFilter = $books->filter(function ($book) {
                    return $book['status'] === Book::statusAvailable;
                });
                $totalItems = $resultFilter->count();
                $resultFilterPaginate = new LengthAwarePaginator($resultFilter
                    ->forPage($currentPage, $itemsPerPage), $totalItems, $itemsPerPage, $currentPage);
                return $resultFilterPaginate;
            }
            else if ($type == 'lock') {
                $resultFilter = $books->filter(function ($book) {
                    return $book['status'] === Book::statusLock;
                });
                $totalItems = $resultFilter->count();
                $resultFilterPaginate = new LengthAwarePaginator($resultFilter
                    ->forPage($currentPage, $itemsPerPage), $totalItems, $itemsPerPage, $currentPage);
                return $resultFilterPaginate;
            }
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function getBookWithAuthorAndCategory()
    {
        try {
            Session::put('books',Book::with('category','authorBook','authorBook.authorInfo')->get());
            return Book::with('category','authorBook','authorBook.authorInfo')->paginate('10');
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getBookByCategoryParent($categoryParentId)
    {
        try {
            $books = Book::whereHas('category',function ($query) use ($categoryParentId) {
                $query->where('category_parent_id',$categoryParentId);
            })->with('category','authorBook','authorBook.authorInfo')->paginate('10');
            Session::put('books',Book::whereHas('category',function ($query) use ($categoryParentId) {
                $query->where('category_parent_id',$categoryParentId);
            })->with('category','authorBook','authorBook.authorInfo')->get());
            return $books;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function filterBookByRangeOfYear(Collection $books ,$minYear, $maxYear)
    {
        try {
            $itemsPerPage = 10;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $resultFilter = $books->whereBetween('year_publish',[$minYear,$maxYear]);
            $totalItems = $resultFilter->count();
            $resultFilterPaginate = new LengthAwarePaginator($resultFilter
                ->forPage($currentPage, $itemsPerPage), $totalItems, $itemsPerPage, $currentPage);
            return new ResourceCollection($resultFilterPaginate);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getBookForHomePage()
    {
        try {
            $books = $this->model::where('status',1)->take(8)->get();
            return $books;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getBookByCategoryForUser($categoryId) {
        try {
            $books = $this->model
                ->where('category_id',$categoryId)
                ->where('status',1)
                ->with('category','authorBook','authorBook.authorInfo');
            Session::put('bookAsc',$books->orderBy('price_rent','asc')->paginate(12));
            Session::put('bookDesc',$books->orderBy('price_rent','desc')->paginate(12));
            Session::put('bookDefault',$books->paginate(12));
            return $books->paginate(12);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getBookForAllBookPage()
    {
        try {
            $books = $this->model;
            Session::put('bookAsc',$books->orderBy('price_rent','asc')->paginate(12));
            Session::put('bookDesc',$books->orderBy('price_rent','desc')->paginate(12));
            Session::put('bookDefault',$books->paginate(12));
            return $books->paginate(12);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getDetailBook($bookId)
    {
        try {
            $books = $this->model->where('id',$bookId)->with('authorBook.authorInfo')->first();
            return $books;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function filterBook($arrFilter)
    {
        try {
            $books = $this->model;
            if ($arrFilter['name']!="") {
                $books = $books->where('name','like','%'.$arrFilter['name'].'%');
            }
            if ($arrFilter['status']!="") {
                $books = $books->where('status',$arrFilter['status']);
            }
            if ($arrFilter['category_parent_id']!="") {
                $books = $books->whereHas('category',function ($query) use($arrFilter) {
                    $query->where('category_parent_id',$arrFilter['category_parent_id']);
                });
                if ($arrFilter['category_children_id']!="") {
                    $books = $books->where('category_id',$arrFilter['category_children_id']);
                }
            }
            if ($arrFilter['minYear']!="" && $arrFilter['maxYear']!="") {
                $books = $books->whereBetween('year_publish',[$arrFilter['minYear'],$arrFilter['maxYear']]);
            }
            return $books->with('category','authorBook','authorBook.authorInfo')->paginate('10');
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function searchBook($name)
    {
        try {
            $resultSearch = $this->model->where('name','like','%'.$name.'%')->paginate(10);
            return $resultSearch;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }


    public function countTotalBook()
    {
        try {
            $books = $this->model->get();
            $countTotalBook = 0;
            foreach ($books as $book) {
                $countTotalBook += $book->quantity;
            }
            return $countTotalBook;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getStatiѕticsOfBook($minDate, $maxDate) {
        try {
            $book = $this->model
                ->leftJoin('detail_history_rent_book','detail_history_rent_book.book_id','book.id')
                ->leftJoin('history_rent_book','detail_history_rent_book.history_rent_book_id','history_rent_book.id')
                ->join('author_book','author_book.book_id','book.id')
                ->join('author_info','author_info.id','author_book.author_id')
                ->join('category','category.id','book.category_id')
                ->selectRaw('book.id,book.name,category.category_name,author_info.author_name,
                            coalesce(sum(case when(history_rent_book.status = 1 or history_rent_book.status=2)
                                and history_rent_book.rent_date >= "'.$minDate.'"
                                and history_rent_book.rent_date <= "'.$maxDate.'"
                                then detail_history_rent_book.quantity end),0)
                                as countBookRentInMonth,
                            coalesce(sum(case when(history_rent_book.status = 1 or history_rent_book.status=2)
                                and history_rent_book.rent_date >= "'.$minDate.'"
                                and history_rent_book.rent_date <= "'.$maxDate.'"
                                then detail_history_rent_book.quantity*book.price_rent end),0)
                                as totalPriceRentInMonth'
                )
                ->groupBy('book.name','book.id','category.category_name','author_info.author_name')->get();
            return $book;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getInformationBookMissing()
    {
        try {
            $book = $this->model
                ->leftJoin('detail_history_rent_book','book.id','detail_history_rent_book.book_id')
                ->leftJoin('history_rent_book','detail_history_rent_book.history_rent_book_id','history_rent_book.id')
                ->join('author_book','author_book.book_id','book.id')
                ->join('author_info','author_info.id','author_book.author_id')
                ->join('category','category.id','book.category_id')
                ->selectRaw('book.id,book.name,
                            category.category_name,
                            author_info.author_name,
                            coalesce(sum(case when history_rent_book.status = 1
                                and history_rent_book.expiration_date < now()
                                then detail_history_rent_book.quantity end),0)
                                as countBookMissing'
                )
                ->groupBy('book.id','book.name','category.category_name','author_info.author_name')
                ->get();
            return $book;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getBookByAllAttribute($collection,$categoryId)
    {
        try {
            $book = $this->model
                ->where('name',$collection['name'])
                ->where('year_publish',$collection['year_publish'])
                ->where('price_rent',$collection['price_rent'])
                ->where('weight',$collection['weight'])
                ->where('total_page',$collection['total_page'])
                ->where('description',$collection['description'])
                ->where('category_id',$categoryId)
                ->get()
                ->first();
            return $book;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
