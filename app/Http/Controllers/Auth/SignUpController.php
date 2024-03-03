<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\MailRegisterService;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class SignUpController extends Controller
{
    public function show()
    {
        return view('auth.signup');
    }

    public function store(Request $request)
    {
        $email = $request->email;
        $user = UserInfo::query()->where('email' ,$email)->first();
        if($user) return response(['statusCode' => 200, 'user' => $user->user()]);
        $token = Crypt::encryptString($email.'|'. Carbon::now()->addHours(3));
        Mail::to($email)->send(new MailRegisterService( $token));
        return response()->json([
            'statusCode' => 200
        ]);
    }
}
