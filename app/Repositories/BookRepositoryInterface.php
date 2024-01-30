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
    public function getBookWithAuthorAndCategory();

    public function getBookByCategory($categoryId);

    public function getBookByName($bookName);

    public function sortBookByYearPublish (Collection $books,$type);

    public function filterBookByStatus (Collection $books,$type);

    public function getBookByCategoryParent($categoryParentId);

    public function filterBookByRangeOfYear(Collection $books ,$minYear, $maxYear);

    public function getBookForHomePage();

    public function getBookByCategoryForUser($categoryId);

    public function getBookForAllBookPage();

    public function getDetailBook($bookId);

    public function filterBook($arrFilter);


}
