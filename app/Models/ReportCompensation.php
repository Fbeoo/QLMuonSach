<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCompensation extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'quantity',
        'book_id',
        'user_id',
    ];
    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
    public function book() {
        return $this->belongsTo(Book::class,'book_id');
    }
}
