<?php

namespace App\Models;

use App\Traits\Uuidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory, Uuidable;

    protected $primary = 'id';

    public $incrementing = false;

    protected $fillable = [
        'email',
        'phone_number',
        'dob',
        'name',
        'description',
        'id_number',
        'user_id'
    ];

    public $timestamps = true;

    public function user(User $user)
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
