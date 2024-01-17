<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthorBook extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'author_book';
    protected $fillable = [
        'book_id',
        'author_id',
    ];
    public function book() {
        return $this->belongsTo(Book::class,'book_id');
    }
    public function authorInfo() {
        return $this->belongsTo(AuthorInfo::class,'author_id');
    }
}
