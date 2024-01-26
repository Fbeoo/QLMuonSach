<?php
namespace App\Repositories\Eloquent;
use App\Repositories\BookRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\ResourceCollection;
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
            return $this->model->where('category_id',$categoryId)->get();
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getBookByName($bookName)
    {
        try {
            return $this->model::where('name','like','%'.$bookName.'%')->get();
        }catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
    }



    public function sortBookByYearPublish(Collection $collection, $type)
    {
        try {
            if ($type == "desc") {
                return new ResourceCollection($collection->sortByDesc('year_publish'));
            } else if ($type == "asc") {
                return new ResourceCollection($collection->sortBy('year_publish'));
            }
        } catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }
}
