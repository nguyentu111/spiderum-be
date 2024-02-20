<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Uuidable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Uuidable;

    protected $primary = 'id';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'alias',
        'avatar_url',
        'facebook_id',
        'username',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function userInfo(): HasOne
    {
        return $this->hasOne(UserInfo::class);
    }

    public function followers(): HasMany
    {
        return $this->hasMany(UserFollower::class, 'target_id', 'id');
    }

    public function followings(): HasMany
    {
        return $this->hasMany(UserFollower::class, 'source_id', 'id');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'user_like_posts', 'user_id', 'post_id');
    }

    public function series(): HasMany
    {
        return $this->hasMany(Series::class, 'author_id', 'id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id', 'id');
    }
}
