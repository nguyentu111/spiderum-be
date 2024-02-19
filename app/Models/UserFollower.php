<?php

namespace App\Models;

use App\Traits\Uuidable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFollower extends Model
{
    use HasFactory, Uuidable;

    protected $primary = 'id';

    public $incrementing = false;

    protected $fillable = [
        'source_id',
        'target_id',
    ];

    public $timestamps = true;

    public function source() {
        return $this->belongsTo(User::class);
    }

    public function target() {
        return $this->belongsTo(User::class);
    }

    public function scopeFindUserFollower(Builder $query, string $sourceId, string $targetId)
    {
        $query->where('source_id', $sourceId)->where('target_id', $targetId);
    }
}
