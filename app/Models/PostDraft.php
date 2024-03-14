<?php

namespace App\Models;

use App\Traits\Uuidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostDraft extends Model
{
    use HasFactory, Uuidable;

    protected $primary = 'id';

    public $incrementing = false;
  /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = ['author'];
    protected $fillable = [
        'id',
        'name',
        'content',
        'author_id',
        'description'
    ];
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
