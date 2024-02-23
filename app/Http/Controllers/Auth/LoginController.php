<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\UserInfo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function show() {
        return view('auth.login');
    }

    public function store(LoginRequest $request) {
        $user = User::where('username', $request->email)->first();
        if ($user) {
            if (!Auth::attempt([
                'username' => $request->email,
                'password' => $request->password,
            ])) {
                return redirect()->back()->with([
                    'errorMessage' => 'Vui lòng kiểm tra tên đăng nhập và mật khẩu.'
                ]);
            }
            $user = Auth::user();

            $token = $user->createToken('main')->plainTextToken;

            $cookie = Cookie::make('token', $token, 864);

            return redirect()->to(env('FRONT_END_URL'))->withCookie($cookie);
        }

        $userInfo = UserInfo::where('email', $request->email)->first();

        if ($userInfo && Hash::check($request->password, $userInfo->user()->password)) {

            $token = $userInfo->user()->createToken('main')->plainTextToken;

            $cookie = Cookie::make('token', $token, 864);

            return Redirect::away(env('FRONT_END_URL'))->withCookie($cookie);
        }

        return redirect()->back()->with([
            'errorMessage' => 'Vui lòng kiểm tra tên đăng nhập và mật khẩu.'
        ]);
    }
}
