<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phone_number',
        'dob',
        'name',
        'description',
        'id_number'
    ];

    protected $timestamps = true;

    public function user(User $user)
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
