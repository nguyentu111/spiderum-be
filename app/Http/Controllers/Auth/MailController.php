<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\MailRegisterService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class MailController extends Controller
{
    public function show(Request $request)
    {
        return view('auth.mail-register');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'email', 'max:255']
            ]);

            $email = $validated['email'];
            $token = $this->createToken($email);

            Mail::to($email)->send(new MailRegisterService($token));

            return response()->json([
                'message' => 'Email xác nhận đã được gửi đến hòm thư ' . $email . ' của bạn.',
                'statusCode' => 200
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Gửi email thất bại.',
                'error' => $exception->getMessage(),
                'statusCode' => 500
            ]);
        }
    }

    public function createToken(string $email): string {
        $now = Carbon::now();

        $tokenScript = $email . '|' . $now->addMinutes(30);
        $token = Crypt::encryptString($tokenScript);

        return $token;
    }
}
