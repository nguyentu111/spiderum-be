<?php

namespace App\Http\Controllers;

use App\Rules\ReCaptchaV3;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use ReCaptcha\ReCaptcha;

class UserController extends Controller
{
    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        $token = $request->query('token');

        $decodeToken = Crypt::decryptString($token);
        [$email, $expiryTime] = explode('|', $decodeToken);

        $expiryTime = Carbon::parse($expiryTime);
        if ($expiryTime->lt(Carbon::now())) {
            return view('user.create-form', [
                'email' => $email,
                'errorMessage' => 'Có lỗi xảy ra. Xin vui lòng thử lại sau.'
            ]);
        } else {
            return view('user.create-form', [
                'email' => $email,
                'errorMessage' => ''
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'g-recaptcha-response' => ['required', new ReCaptchaV3('submitContact')]
        ]);

         return redirect()->back()->with('message', 'Thank you for contacting us. Your message has been sent. ');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
