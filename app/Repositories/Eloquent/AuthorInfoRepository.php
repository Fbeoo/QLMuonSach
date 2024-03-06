<?php
namespace App\Repositories\Eloquent;
use App\Models\AuthorInfo;
use App\Repositories\AuthorInfoRepositoryInterface;

class AuthorInfoRepository extends BaseRepository implements AuthorInfoRepositoryInterface {

    public function getAuthorForHomePage()
    {
        try {
            $authors = AuthorInfo::take(6)->get();
            return $authors;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getAuthorForAllAuthorPage()
    {
        try {
            $authors = AuthorInfo::paginate('8');
            return $authors;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function getAuthorByName($authorName)
    {
        try {
            $author = $this->model
                ->where('author_name',$authorName)
                ->get()
                ->first();
            return $author;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
