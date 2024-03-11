<?php

namespace App\Models;

use App\Traits\Uuidable;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Series extends Model
{
    use HasFactory, Uuidable;

    protected $primary = 'id';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail',
        'is_shown',
        'author_id'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function saves(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_save_series', 'series_id', 'user_id');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'series_posts','series_id','post_id');
    }

    public function scopeFindBySlug(Builder $query, string $slug)
    {
        $query->where('slug', $slug);
    }
}
