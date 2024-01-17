<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $table = 'user';
    protected $fillable = [
        'name',
        'dob',
        'password',
        'mail',
        'address',
        'status'
    ];
    public function historyRentBook() {
        return $this->hasMany(HistoryRentBook::class,'user_id');
    }
    public function commentedBook() {
        return $this->belongsToMany(Book::class,'comment_book');
    }
    public function compensateBook() {
        return $this->belongsToMany(Book::class,'report_compensation');
    }
}
