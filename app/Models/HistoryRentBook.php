<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryRentBook extends Model
{
    use HasFactory;
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
