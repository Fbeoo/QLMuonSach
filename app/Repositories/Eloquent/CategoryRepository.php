<?php
namespace App\Repositories\Eloquent;
use App\Repositories\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface {

    public function getCategoryParent()
    {
        return $this->model->where('category_parent_id',null)->get();
    }

    public function getCategoryChild()
    {
        return $this->model->where('category_parent_id','<>',null)->get();
    }

    public function getCategoryChildByCategoryParentId($categoryParentId)
    {
        return $this->model->where('category_parent_id',$categoryParentId)->get();
    }
}
