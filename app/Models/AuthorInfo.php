<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'author_name',
        'dob',
        'address',
    ];
    public function book() {
        return $this->belongsToMany(Book::class);
    }
}
