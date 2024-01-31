<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 */
class HistoryRentBook extends Model
{
    use HasFactory, SoftDeletes;

    const statusPending = 0;
    const statusBorrowing = 1;
    const statusReturned = 2;
    const statusRefuse = 3;

    /**
     * @var string
     */
    protected $table = 'history_rent_book';
    /**
     * @var string[]
     */
    protected $fillable = [
        'rent_date',
        'expiration_date',
        'return_date',
        'status',
        'total_price',
        'user_id',
    ];

    public function detailHistoryRentBook() {
        return $this->hasMany(DetailHistoryRentBook::class,'history_rent_book_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
