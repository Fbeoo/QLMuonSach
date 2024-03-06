<?php
namespace App\Repositories;
interface AuthorBookRepositoryInterface extends EloquentRepositoryInterface {
    /**
     * @param $bookId
     * @return mixed
     */
    public function getAuthorBook ($bookId);

    public function getBookOfAuthor($authorId);
}
