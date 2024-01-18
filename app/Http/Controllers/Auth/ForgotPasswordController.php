<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function show() {
        return view('auth.login');
    }

    public function store() {
        return view('auth.login');
    }
}
