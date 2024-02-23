<?php

namespace App\Models;

use App\Traits\Uuidable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory, Uuidable;

    protected $primary = 'id';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'content',
        'like',
        'view',
        'comment',
        'is_shown',
        'author_id',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_like_posts', 'post_id', 'user_id');
    }

    public function dislikes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_dislike_posts', 'post_id', 'user_id');
    }

    public function likedUserIds(): Attribute
    {
        return Attribute::make(
            get: function () {
                $likes = $this->likes;

                return $likes->map(function ($likedUser) {
                    return $likedUser->getKey();
                });
            }
        );
    }

    public function dislikedUserIds(): Attribute
    {
        return Attribute::make(
            get: function () {
                $dislikes = $this->dislikes;

                return $dislikes->map(function ($dislikedUser) {
                    return $dislikedUser->getKey();
                });
            }
        );
    }

    public function series(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'series_posts', 'series_id', 'post_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function scopeFindBySlug(Builder $query, string $slug)
    {
        $query->where('slug', $slug);
    }
}
