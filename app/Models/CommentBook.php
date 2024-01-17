<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentBook extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'comment_book';
    protected $fillable = [
        'content',
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
