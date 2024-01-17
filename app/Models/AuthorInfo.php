<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthorInfo extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'author_info';
    protected $fillable = [
        'author_name',
        'dob',
        'address',
    ];
    public function book() {
        return $this->belongsToMany(Book::class);
    }
}
