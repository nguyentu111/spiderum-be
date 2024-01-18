<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function show() {
        return view('auth.login');
    }

    public function store() {
        return view('auth.login');
    }
}
