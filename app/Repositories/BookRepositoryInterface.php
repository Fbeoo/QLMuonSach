<?php
namespace App\Repositories;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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

    public function getBookByName($bookName);

    public function sortBookByYearPublish (Collection $collection,$type);
}
