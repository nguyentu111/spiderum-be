<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SignUpController extends Controller
{
    public function show()
    {
        return view('auth.signup');
    }

    public function store()
    {

    }
}
