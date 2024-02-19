<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primary = 'id';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug'
    ];
}
