<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'year_publish',
        'price',
        'weight',
        'total_page',
        'thumbnail',
        'status',
        'category_id',
    ];
    public function category() {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function authorInfo() {
        return $this->belongsToMany(AuthorInfo::class);
    }
    public function historyRentBook() {
        return $this->belongsToMany(HistoryRentBook::class);
    }
    public function commentingByUser() {
        return $this->belongsToMany(User::class,'comment_book');
    }
    public function compensatingByUser() {
        return $this->belongsToMany(User::class,'report_compensation');
    }
}
