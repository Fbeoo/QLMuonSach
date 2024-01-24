<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 */
class DetailHistoryRentBook extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'detail_history_rent_book';
    /**
     * @var string[]
     */
    protected $fillable = [
        'expiration_date',
        'quantity',
        'return_date',
        'status',
        'book_id',
        'history_rent_book_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book() {
        return $this->belongsTo(Book::class,'book_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function historyRentBook() {
        return $this->belongsTo(HistoryRentBook::class,'history_rent_book_id');
    }
}
