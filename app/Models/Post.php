<?php

namespace App\Models;

use App\Traits\Uuidable;
use Illuminate\Contracts\Database\Eloquent\Builder;
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
