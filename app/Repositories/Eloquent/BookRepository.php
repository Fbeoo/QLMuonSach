<?php
namespace App\Repositories\Eloquent;
use App\Models\Book;
use App\Repositories\BookRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
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
            Session::put('books',$resultFilter);
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
            $books = $this->model::where('status',1)->paginate('12');
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
                ->with('category','authorBook','authorBook.authorInfo')
                ->paginate(12);
            return $books;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
