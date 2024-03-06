<?php
namespace App\Repositories;
/**
 *
 */
interface CategoryRepositoryInterface extends EloquentRepositoryInterface {
    /**
     * @return mixed
     */
    public function getCategoryParent();

    /**
     * @return mixed
     */
    public function getCategoryChild();

    /**
     * @param $categoryParentId
     * @return mixed
     */
    public function getCategoryChildByCategoryParentId($categoryParentId);

    public function getCategoryParentByName($categoryName);

    public function getCategoryChildrenByName($categoryName,$categoryParentId);
}
