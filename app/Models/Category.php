<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'category';
    protected $fillable = [
        'category_name',
        'category_parent_id',
    ];
    public function book() {
        return $this->hasMany(Book::class,'category_id');
    }
}
