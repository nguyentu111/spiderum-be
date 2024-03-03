<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\UserInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
  
    public function store(Request $request)
    {
        $user = User::where('username', $request->email)->first();
        if ($user) {
            if (
                !Auth::attempt([
                    'username' => $request->email,
                    'password' => $request->password,
                ])
            ) {
                return response()->json([
                    'message' => 'Vui lòng kiểm tra tên đăng nhập và mật khẩu.',
                    'statusCode' => 400 
                ],400);
            }
            $user = Auth::user();

            $token = $user->createToken('main')->plainTextToken;

            return response()->json([
                'statusCode' => 200,
                'token' => $token,
                'user' =>  $user->load('userInfo')
            ]);
        }

        $userInfo = UserInfo::where('email', $request->email)->first();
        if ($userInfo && Hash::check($request->password,$userInfo->user()->first()->password)) {
            $token =$userInfo->user()->first()->createToken('main')->plainTextToken;
            return response()->json([
                'statusCode' => 200,
                'token' => $token,
                'user' => $userInfo->user()->with('userInfo')->first()
            ]);
        }

        return response()->json([
            'message' => 'Vui lòng kiểm tra tên đăng nhập và mật khẩu.',
            'statusCode' => 400 
        ],400);
    }
}
