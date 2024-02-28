<?php

namespace App\Jobs;

use App\Models\AuthorBook;
use App\Models\AuthorInfo;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportBook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bookRepository;

    protected $categoryRepository;

    protected $authorInfoRepository;

    protected $authorBookRepository;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct($categoryRepository,$bookRepository,$authorBookRepository,$authorInfoRepository,$data)
    {
        $this->bookRepository = $bookRepository;
        $this->authorBookRepository = $authorBookRepository;
        $this->categoryRepository = $categoryRepository;
        $this->authorInfoRepository = $authorInfoRepository;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            foreach ($this->data as $item) {

                $categoryParent = $this->categoryRepository->getCategoryParentByName($item['category_parent']);

                if ($categoryParent === null) {
                    $categoryParent = $this->addCategory($item['category_parent'],null);

                    $categoryChildren = $this->addCategory($item['category_children'],$categoryParent->id);
                }

                else {
                    $categoryChildren = $this->categoryRepository->getCategoryChildrenByName($item['category_children'],$categoryParent->id);

                    if ($categoryChildren === null) {
                        $categoryChildren = $this->addCategory($item['category_children'],$categoryParent->id);
                    }
                }

                $checkBookExist = $this->bookRepository->getBookByAllAttribute ($item,$categoryChildren->id);

                if ($checkBookExist === null) {
                    $newBook = $this->addBook($item,$categoryChildren->id);

                    $authorInfo = $this->authorInfoRepository->getAuthorByName($item['author']);


                    if ($authorInfo === null) {
                        $authorInfo = $this->addAuthorInfo($item['author']);
                    }

                    $this->addAuthorBook($newBook->id,$authorInfo->id);
                }

                else {
                    $checkBookExist->quantity += intval($item['quantity']);
                    $this->bookRepository->update($checkBookExist);
                }
            }
        }catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }
    private function storeFileImage($urlImage) {
        try {
            $content = file_get_contents($urlImage);
            $tempFilePath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
            file_put_contents($tempFilePath,$content);
            $file = new File($tempFilePath);
            $fileImageSave = Storage::putFile('public/img',$file);
            return $fileImageSave;
        }catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function addCategory($categoryName,$categoryParentId) {
        try {
            $category = new Category();

            $category->category_name = $categoryName;
            $category->category_parent_id = $categoryParentId;

            $newCategory = $this->categoryRepository->add($category);

            return $newCategory;
        }catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function addBook($collection,$categoryId) {
        try {
            $fileThumbnailBookSave = $this->storeFileImage($collection['thumbnail']);

            $book = new Book();

            $book->name = $collection['name'];
            $book->year_publish = intval($collection['year_publish']);
            $book->price_rent = intval($collection['price_rent']);
            $book->weight = intval($collection['weight']);
            $book->total_page = intval($collection['total_page']);
            $book->quantity = intval($collection['quantity']);
            $book->thumbnail = Str::after($fileThumbnailBookSave,'/');
            $book->description = $collection['description'];
            $book->status = intval($collection['status']);
            $book->category_id = $categoryId;

            $newBook = $this->bookRepository->add($book);

            return $newBook;
        }catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function addAuthorBook($bookId,$authorId) {
        try {
            $authorBook = new AuthorBook();

            $authorBook->book_id = $bookId;
            $authorBook->author_id = $authorId;

            $this->authorBookRepository->add($authorBook);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function addAuthorInfo($authorName) {
        try {
            $urlImageAuthor = 'https://loremflickr.com/320/240/face';
            $fileImageAuthorSave = $this->storeFileImage($urlImageAuthor);

            $author = new AuthorInfo();

            $author->author_name = $authorName;
            $author->author_image = Str::after($fileImageAuthorSave,'/');

            $newAuthorInfo = $this->authorInfoRepository->add($author);

            return $newAuthorInfo;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
