<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{

    public function __invoke(Request $request) {
        $user = $request->user();

        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return redirect('login')->back()->with([
            'errorMessage' => 'Vui lòng kiểm tra tên đăng nhập và mật khẩu.'
        ]);
    }
}

