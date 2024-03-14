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
  /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['comments_count','is_saved','user_action','point'];
    protected $with = ['author','categories'];
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
        'description'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_like_posts','post_id', 'user_id');
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
        return $this->belongsToMany(Series::class, 'series_posts', 'post_id','series_id');
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_categories', 'post_id','category_id');
    }
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tags', 'post_id','tag_id');
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }
    public function commentsCount() : Attribute{
        return new Attribute(
            get: fn () => $this->comments()->count()
        );
    }
    public function savedByUsers(){
        return $this->belongsToMany(User::class,'user_save_posts','post_id','user_id');
    }
    public function isSaved(): Attribute {
        $user = auth('sanctum')->user();
        return new Attribute(
            get :function () use ($user){
                if($user) {
                    return $this->savedByUsers()->where('user_id',$user->id)->exists();
                }
                else return false;
            }
        );
    }
    public function userAction() :Attribute{
        $user = auth('sanctum')->user();
        return new Attribute(
            get :function () use ($user){
                if($user) {
                    $isLiked = $this->likes()->where('user_id',$user->id)->exists();
                    $isDisLiked = $this->dislikes()->where('user_id',$user->id)->exists();
                    return   $isLiked  ?  1 :  ( $isDisLiked ?  -1 : 0);
                }
                else return 0;
            }
        );
    }
    public function point():Attribute {
        return new Attribute(
            get :function () {
                    $likeCount = $this->likes()->count();
                    $dislikeCount = $this->dislikes()->count();
                    return    $likeCount - $dislikeCount;
            }
        );
    }

}
