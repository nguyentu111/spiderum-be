<?php

namespace App\Models;

use App\Traits\Uuidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFollower extends Model
{
    use HasFactory, Uuidable;

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
}
