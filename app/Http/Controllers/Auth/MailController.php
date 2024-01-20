<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function show(Request $request)
    {
        // dd(Mail::to($request->get('email'))->send(new MailService()));
        return view('auth.mail');
    }

    public function store()
    {

    }
}
