<?php

namespace App\Imports;

use App\Models\AuthorBook;
use App\Models\AuthorInfo;
use App\Models\Book;
use App\Models\Category;
use App\Repositories\AuthorBookRepositoryInterface;
use App\Repositories\AuthorInfoRepositoryInterface;
use App\Repositories\BookRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use Aws\S3\S3Client;
use GuzzleHttp\Client;
use Illuminate\Http\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BookImport implements ToCollection, WithHeadingRow, WithValidation
{

    protected $categoryRepository;

    protected $authorInfoRepository;

    protected $authorBookRepository;

    protected $bookRepository;

    /**
     * @param $categoryRepository
     * @param $authorInfoRepository
     * @param $authorBookRepository
     * @param $bookRepository
     */
    public function __construct($categoryRepository, $authorInfoRepository, $authorBookRepository, $bookRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->authorInfoRepository = $authorInfoRepository;
        $this->authorBookRepository = $authorBookRepository;
        $this->bookRepository = $bookRepository;
    }

    /**
     * @param $categoryRepository
     * @param $authorInfoRepository
     * @param $authorBookRepository
     * @param $bookRepository
     */



    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try {
            foreach ($collection as $row) {
                $category = new Category();
                $categoryParent = $this->categoryRepository->getCategoryParentByName($row['category_parent']);
                if ($categoryParent === null) {
                    $category->category_name = $row['category_parent'];
                    $category->category_parent_id = null;
                    $categoryParent = $this->categoryRepository->add($category);

                    $category->category_name = $row['category_children'];
                    $category->category_parent_id = $categoryParent->id;
                    $categoryChildren = $this->categoryRepository->add($category);
                }
                else {
                    $categoryChildren = $this->categoryRepository->getCategoryChildrenByName($row['category_children'],$categoryParent->id);
                    if ($categoryChildren === null) {
                        $category->category_name = $row['category_children'];
                        $category->category_parent_id = $categoryParent->id;
                        $categoryChildren = $this->categoryRepository->add($category);
                    }
                }

                $checkBookExist = $this->bookRepository->getBookByAllAttribute ($row['name'],
                                                                                $row['year_publish'],
                                                                                $row['price_rent'],
                                                                                $row['weight'],
                                                                                $row['total_page'],
                                                                                $row['description'],
                                                                                $categoryChildren->id);
                if ($checkBookExist === null) {
                    $urlImage = $row['thumbnail'];
                    $content = file_get_contents($urlImage);
                    $tempFilePath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
                    file_put_contents($tempFilePath,$content);
                    $file = new File($tempFilePath);
                    $fileThumbnailBookSave = Storage::putFile('public/img',$file);

                    $book = new Book();
                    $book->name = $row['name'];
                    $book->year_publish = intval($row['year_publish']);
                    $book->price_rent = intval($row['price_rent']);
                    $book->weight = intval($row['weight']);
                    $book->total_page = intval($row['total_page']);
                    $book->quantity = intval($row['quantity']);
                    $book->thumbnail = Str::after($fileThumbnailBookSave,'/');
                    $book->description = $row['description'];
                    $book->status = intval($row['status']);
                    $book->category_id = $categoryChildren->id;
                    $newBook = $this->bookRepository->add($book);

                    $authorInfo = $this->authorInfoRepository->getAuthorByName($row['author']);
                    if ($authorInfo === null) {
                        $urlImageAuthor = 'https://loremflickr.com/320/240/face';
                        $contentImageAuthor = file_get_contents($urlImageAuthor);
                        $tempFilePathAuthor = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
                        file_put_contents($tempFilePathAuthor,$contentImageAuthor);
                        $fileImageAuthor = new File($tempFilePathAuthor);
                        $fileImageAuthorSave = Storage::putFile('public/img',$fileImageAuthor);

                        $author = new AuthorInfo();

                        $author->author_name = $row['author'];
                        $author->author_image = Str::after($fileImageAuthorSave,'/');

                        $authorInfo = $this->authorInfoRepository->add($author);
                    }
                    $authorBook = new AuthorBook();
                    $authorBook->book_id = $newBook->id;
                    $authorBook->author_id = $authorInfo->id;
                    $this->authorBookRepository->add($authorBook);
                }
                else {
                    $checkBookExist->quantity += intval($row['quantity']);
                    $this->bookRepository->update($checkBookExist);
                }
            }
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'year_publish' => 'required|integer|min:1',
            'price_rent' => 'required|integer|min:1',
            'weight' => 'required|integer|min:1',
            'total_page' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'category_children' => 'required',
            'category_parent' => 'required',
            'author' => 'required',
            'thumbnail' => 'required',
            'description' => 'required'
        ];
    }
    public function customValidationMessages()
    {
        return [
            'name.required' => 'Cột name đang có ô trống',

            'year_publish.required' => 'Cột year_publish đang có ô trống',
            'year_publish.integer' => 'Cột year_publish đang có ô không phải dạng số',
            'year_publish.min' => 'Cột year_publish đang có ô nhỏ hơn 1',

            'price_rent.required' => 'Cột price_rent đang có ô trống',
            'price_rent.integer' => 'Cột price_rent đang có ô không phải dạng số',
            'price_rent.min' => 'Cột price_rent đang có ô nhỏ hơn 1',

            'weight.required' => 'Cột weight đang có ô trống',
            'weight.integer' => 'Cột weight đang có ô không phải dạng số',
            'weight.min' => 'Cột weight đang có ô nhỏ hơn 1',

            'total_page.required' => 'Cột total_page đang có ô trống',
            'total_page.integer' => 'Cột total_page đang có ô không phải dạng số',
            'total_page.min' => 'Cột total_page đang có ô nhỏ hơn 1',

            'quantity.required' => 'Cột quantity đang có ô trống',
            'quantity.integer' => 'Cột quantity đang có ô không phải dạng số',
            'quantity.min' => 'Cột quantity đang có ô nhỏ hơn 1',

            'category_children.required' => 'Cột category_children đang có ô trống',

            'category_parent.required' => 'Cột category_parent đang có ô trống',

            'author.required' => 'Cột author đang có ô trống',

            'thumbnail.required' => 'Cột thumbnail đang cố ô trống',

            'description.required' => 'Cột description đang có ô trống'
        ];
    }
}
