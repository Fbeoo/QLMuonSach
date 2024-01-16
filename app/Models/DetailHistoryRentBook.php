<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailHistoryRentBook extends Model
{
    use HasFactory;
    protected $fillable = [
        'expiration_date',
        'quantity',
        'return_date',
        'status',
        'book_id',
        'history_rent_book_id',
    ];
    public function book() {
        return $this->belongsTo(Book::class,'book_id');
    }
    public function historyRentBook() {
        return $this->belongsTo(HistoryRentBook::class,'history_rent_book_id');
    }
}
