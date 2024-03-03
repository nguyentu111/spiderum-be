<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPassword;
use App\Mail\MailForgotPassService;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function show() {
        return view('auth.forgot-password');
    }

    public function sendmail(Request $request) {
        $email = $request->email;
        $user = UserInfo::query()->where('email' ,$email)->first();
        if(!$user) return response(['statusCode' => 400, 'message' =>"Không tìm thấy user"],400);
        $token = Crypt::encryptString($email.'|'. Carbon::now()->addHours(3));
        Mail::to($email)->send(new MailForgotPassService( $token, $user->user()->first()->username));
        return response()->json([
            'statusCode' => 200
        ]);
    }
    public function resetPassword(ResetPassword $request)
    {
        $newPassword = $request->password;
        $email = $request->email;
        $userInfo = UserInfo::where('email', $email)->first();
    
        if (!$userInfo) {
            return response([
                'statusCode' => 400,
                'message' => 'Không tìm thấy người dùng'
            ]);
        }
    
        $user = $userInfo->user;
        $user->update(['password' => $newPassword]);
    
        return response(['statusCode' => 200]);
    }
    
}
