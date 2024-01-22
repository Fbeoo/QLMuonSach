<?php
namespace App\Repositories\Eloquent;
use App\Repositories\AuthorBookRepositoryInterface;

class AuthorBookRepository extends BaseRepository implements AuthorBookRepositoryInterface {

    public function getAuthorBook($bookId)
    {
        return $this->model->where('book_id',$bookId)->get();
    }
}
