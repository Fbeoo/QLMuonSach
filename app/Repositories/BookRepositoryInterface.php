<?php
namespace App\Repositories;
/**
 *
 */
interface BookRepositoryInterface extends EloquentRepositoryInterface {
    /**
     * @return mixed
     */
    public function getBookForPaging();

    /**
     * @param $categoryId
     * @return mixed
     */
    public function getBookByCategory($categoryId);
}
