<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUser;
use App\Http\Requests\UpdateUser;
use App\Models\User;
use App\Models\UserInfo;
use App\Supports\UserResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
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

            return Redirect::away(env('FRONT_END_URL'))->withCookie($cookie);
        }
        catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage()
            ]);
            // return redirect()->back()->with([
            //     'errorMessage' => $exception->getMessage()
            // ]);
        }
    }

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Xác thực người dùng thất bại.',
                'status' => 404,
                'data' => $user
            ], 401);
        }

        $userResponse = new UserResponse($user, $user->userInfo);
        return response()->json([
            'message' => 'Lấy thông tin người dùng thành công',
            'status' => 200,
            'data' => $userResponse->getResponse()
        ]);
    }

    public function update(UpdateUser $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 200,
                'message' => 'Token không khả dụng.'
            ]);
        }

        Validator::make($request->all(), [
            'email' => Rule::unique('user_infos', 'email')->ignore($user->userInfo),
        ])->validated();

        try {
            $updatedUser = DB::transaction(function () use ($request, $user) {
                $user->update([
                    'alias' => $request->get('alias')
                ]);
                $user->userInfo()->update([
                    'email' => $request->get('email'),
                    'phone_number' => $request->get('phone_number'),
                    'id_number' => $request->get('id_number'),
                    'dob' => $request->get('dob'),
                    'description' => $request->get('description'),
                    'address' => $request->address
                ]);

                return $user;
            });
            $userInfo = $updatedUser->userInfo;
            $userResponse = new UserResponse($updatedUser, $userInfo);

            return response()->json([
                'status' => 200,
                'message' => 'Cập nhật thông tin thành công.',
                'data' => $userResponse->getResponse()
            ]);
        }
        catch (Exception $exception) {
            return response()->json([
                'status' => 500,
                'errorMessage' => $exception->getMessage()
            ], 500);
        }
    }
}
