<?php
namespace App\Repositories\Eloquent;
use App\Repositories\CategoryRepositoryInterface;
use function PHPUnit\Framework\isEmpty;


/**
 *
 */
class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface {

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getCategoryParent()
    {
        try {
            return $this->model->where('category_parent_id',null)->get();
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getCategoryChild()
    {
        try {
            return $this->model->where('category_parent_id','<>',null)->get();
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param $categoryParentId
     * @return mixed
     * @throws \Exception
     */
    public function getCategoryChildByCategoryParentId($categoryParentId)
    {
        try {
            return $this->model->where('category_parent_id',$categoryParentId)->get();
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getCategoryParentByName($categoryName)
    {
        try {
            $category = $this->model
                ->where('category_name',$categoryName)
                ->where('category_parent_id',null)
                ->get()
                ->first();
            return $category;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getCategoryChildrenByName($categoryName,$categoryParentId)
    {
        try {
            $category = $this->model
                ->where('category_name',$categoryName)
                ->where('category_parent_id',$categoryParentId)
                ->get()
                ->first();
            return $category;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
