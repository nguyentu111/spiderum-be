<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUser;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

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
                'errorMessage' => 'Đường dẫn đã hết hạn. Vui lòng xác thực lại email.'
            ]);
        } else {
            return view('user.create-form', [
                'email' => $email,
                'errorMessage' => ''
            ]);
        }
    }

    public function store(CreateUser $request)
    {
        try {
            $user = DB::transaction(function () use ($request) {
                $user = User::create([
                    'username' => $request->get('username'),
                    'password' => $request->get('password'),
                    'alias' => $request->get('alias'),
                ]);
                UserInfo::create([
                    'email' => $request->get('email'),
                    'phone_number' => $request->get('phone_number') ?? null,
                    'id_number' => $request->get('id_number') ?? null,
                    'user_id' => $user->getKey()
                ]);

                return $user;
            });
            $token = $user->createToken('main')->plainTextToken;
            $cookie = Cookie::make('token', $token, 864);

            return redirect()->to(env('FRONT_END_URL'))->withCookie($cookie);
        }
        catch (Exception $exception) {
            return redirect()->back()->with([
                'errorMessage' => $exception->getMessage()
            ]);
        }
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
