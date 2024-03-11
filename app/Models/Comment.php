<?php

namespace App\Models;

use App\Traits\Uuidable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory, Uuidable;

    protected $primary = 'id';

    public $incrementing = false;

    protected $fillable = [
        'content',
        'parent_id',
        'user_id',
        'post_id',
        'like',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_like_comments', 'comment_id', 'user_id');
    }

    public function dislikes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_dislike_comments','comment_id', 'user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id')->withDefault();
    }

    protected function parents(): Attribute
    {
        return Attribute::make(
            get: function (): Collection {
                $parents = collect();

                $parent = $this->parent;

                while ($parent->getKey()) {
                    $parents->push($parent);
                    $parent = $parent->parent;
                }

                return $parents;
            }
        );
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id','id');
    }
    // public function likes(){
    //     return $this->belongsToMany(User::class, 'user_like_comments','user_id','comment_id');
    // }
    public function childrens()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id')->with('user.userInfo','likes','dislikes');
    }
    public function scopeChildless($q)
    {
        return $q->has('childrens', '=', 0);
    }
}
