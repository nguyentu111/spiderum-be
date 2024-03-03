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
        'address',
        'dob',
        'description',
        'id_number',
        'user_id'
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
