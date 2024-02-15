<?php

namespace App\Models;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 *
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const statusLock = 0;
    const statusNormal = 1;

    const statusInactive = 2;


    /**
     * @var string
     */
    protected $table = 'user';
    /**
     * @var string
     */
    protected $guarded = 'web';

    protected $email = 'mail';
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'dob',
        'mail',
        'address',
        'status',
        'password',
        'remember_token',
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
