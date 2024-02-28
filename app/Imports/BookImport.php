<?php

namespace App\Imports;

use App\Jobs\ImportBook;
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

class BookImport implements ToCollection, WithHeadingRow
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
            $collectionChunk = $collection->chunk(10);
            foreach ($collectionChunk as $item) {
                ImportBook::dispatch($this->categoryRepository,
                    $this->bookRepository,
                    $this->authorBookRepository,
                    $this->authorInfoRepository,
                    $item);
            }
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

//    public function rules(): array
//    {
//        return [
//            'name' => 'required',
//            'year_publish' => 'required|integer|min:1',
//            'price_rent' => 'required|integer|min:1',
//            'weight' => 'required|integer|min:1',
//            'total_page' => 'required|integer|min:1',
//            'quantity' => 'required|integer|min:1',
//            'category_children' => 'required',
//            'category_parent' => 'required',
//            'author' => 'required',
//            'thumbnail' => 'required',
//            'description' => 'required'
//        ];
//    }
//    public function customValidationMessages()
//    {
//        return [
//            'name.required' => @trans('message.columnNameExcelRequired'),
//
//            'year_publish.required' => @trans('message.columnYearPublishExcelRequired'),
//            'year_publish.integer' => @trans('message.columnYearPublishExcelInteger'),
//            'year_publish.min' => @trans('message.columnYearPublishExcelMin'),
//
//            'price_rent.required' => @trans('message.columnPriceRentExcelRequired'),
//            'price_rent.integer' => @trans('message.columnPriceRentExcelInteger'),
//            'price_rent.min' => @trans('message.columnPriceRentExcelMin'),
//
//            'weight.required' => @trans('message.columnWeightExcelRequired'),
//            'weight.integer' => @trans('message.columnWeightExcelInteger'),
//            'weight.min' => @trans('message.columnWeightExcelMin'),
//
//            'total_page.required' => @trans('message.columnTotalPageExcelRequired'),
//            'total_page.integer' => @trans('message.columnTotalPageExcelInteger'),
//            'total_page.min' => @trans('message.columnTotalPageExcelMin'),
//
//            'quantity.required' => @trans('message.columnQuantityExcelRequired'),
//            'quantity.integer' => @trans('message.columnQuantityExcelInteger'),
//            'quantity.min' => @trans('message.columnQuantityExcelMin'),
//
//            'category_children.required' => @trans('message.columnCategoryChildrenExcelRequired'),
//
//            'category_parent.required' => @trans('message.columnCategoryParentExcelRequired'),
//
//            'author.required' => @trans('message.columnAuthorExcelRequired'),
//
//            'thumbnail.required' => @trans('message.columnThumbnailExcelRequired'),
//
//            'description.required' => @trans('message.columnDescriptionExcelRequired')
//        ];
//    }
}
