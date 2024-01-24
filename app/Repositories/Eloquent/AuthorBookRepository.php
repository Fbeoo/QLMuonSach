<?php
namespace App\Repositories\Eloquent;
use App\Repositories\AuthorBookRepositoryInterface;

/**
 *
 */
class AuthorBookRepository extends BaseRepository implements AuthorBookRepositoryInterface {


    /**
     * @param $bookId
     * @return mixed
     * @throws \Exception
     */
    public function getAuthorBook($bookId)
    {
        try {
            return $this->model->where('book_id',$bookId)->get();
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
