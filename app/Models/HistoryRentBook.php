<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoryRentBook extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'history_rent_book';
    protected $fillable = [
        'rent_date',
        'status',
        'total_price',
        'user_id',
    ];
    public function book() {
        return $this->belongsToMany(Book::class);
    }
    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
