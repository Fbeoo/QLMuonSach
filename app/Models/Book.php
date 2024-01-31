<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 */
class Book extends Model
{
    use HasFactory,SoftDeletes;

    const statusAvailable = 1;
    const statusLock = 0;

    /**
     * @var string
     */
    protected $table = 'book';
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'year_publish',
        'price_rent',
        'weight',
        'total_page',
        'thumbnail',
        'quantity',
        'status',
        'description',
        'category_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function authorBook() {
        return $this->hasMany(AuthorBook::class,'book_id','id');
    }

    public function detailHistoryRentBook() {
        return $this->hasMany(DetailHistoryRentBook::class,'book_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function commentingByUser() {
        return $this->belongsToMany(User::class,'comment_book');
    }

}
