<?php
namespace App\Repositories;
interface CategoryRepositoryInterface extends EloquentRepositoryInterface {
    public function getCategoryParent();
    public function getCategoryChild();
    public function getCategoryChildByCategoryParentId($categoryParentId);
}
