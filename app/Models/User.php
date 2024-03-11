<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $primaryKey  = 'id';

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

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_followers','target_id', 'source_id');
    }

    public function followerIds(): Attribute
    {
        return Attribute::make(
            get: function () {
                $followers = $this->followers;

                return $followers->map(function ($follower) {
                    return $follower->getKey();
                })->toArray();
            }
        );
    }

    public function followingIds(): Attribute
    {
        return Attribute::make(
            get: function () {
                $followings = $this->followings;

                return $followings->map(function ($follower) {
                    return $follower->getKey();
                })->toArray();
            }
        );
    }

    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'user_followers', 'source_id', 'target_id');
    }

    public function likePosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'user_like_posts', 'user_id', 'post_id');
    }

    public function dislikePosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'user_dislike_posts', 'user_id', 'post_id');
    }

    public function series(): HasMany
    {
        return $this->hasMany(Series::class, 'author_id', 'id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id', 'id');
    }

    public function savedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'user_save_posts', 'user_id', 'post_id');
    }

    public function seriesSaved(): BelongsToMany
    {
        return $this->belongsToMany(Series::class, 'user_save_series', 'user_id', 'series_id');
    }

    public function likeComments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'user_like_comments', 'comment_id', 'user_id');
    }

    public function dislikeComments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'user_dislike_comments', 'comment_id', 'user_id');
    }
}
