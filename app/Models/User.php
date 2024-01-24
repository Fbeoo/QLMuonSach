<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 *
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'user';
    /**
     * @var string
     */
    protected $guarded = 'web';
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'dob',
        'password',
        'mail',
        'address',
        'status'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function historyRentBook() {
        return $this->hasMany(HistoryRentBook::class,'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function commentedBook() {
        return $this->belongsToMany(Book::class,'comment_book');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function compensateBook() {
        return $this->belongsToMany(Book::class,'report_compensation');
    }
}
