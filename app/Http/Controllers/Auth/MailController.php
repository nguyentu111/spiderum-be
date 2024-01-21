<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function show()
    {
        return view('auth.mail');
    }
    public function __invoke(Request $request)
    {
        $email = $request->email;
        Mail::to($email)->send(new MailService());

        return view('auth.mail');
    }
}
