<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function show()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function store()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            $user = User::query()->where('facebook_id', $facebookUser->id)->first();

            if ($user) {
                Auth::login($user);

                return view('login', compact([
                    'message' => 'Login successfully'
                ]));
            } else {
                $newUser = User::query()->updateOrCreate(
                    [
                        'email' => $facebookUser->email
                    ],
                    [
                        'name' => $facebookUser->name,
                        'facebook_id' => $facebookUser->id,
                        'email' => $facebookUser->email,
                        'alias' => 'none',
                        'password' => encrypt($user->name . env('DEFAULT_PASSWORD')),
                    ]
                );

                Auth::login($newUser);

                return view('login', compact([
                    'message' => 'Login successfully'
                ]));
            }
        } catch (Exception $exception) {
            dd($exception);
        }
    }
}
