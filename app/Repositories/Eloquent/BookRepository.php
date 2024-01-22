<?php
namespace App\Repositories\Eloquent;
use App\Repositories\BookRepositoryInterface;

class BookRepository extends BaseRepository implements BookRepositoryInterface {
    public function getBookForPaging() {
        return $this->model->paginate(4);
    }

    public function getBookByCategory($categoryId)
    {
        return $this->model->where('category_id',$categoryId)->get();
    }
}
