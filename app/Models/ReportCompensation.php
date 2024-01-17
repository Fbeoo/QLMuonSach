<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportCompensation extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'report_compensation';
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
