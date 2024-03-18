<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUser;
use App\Http\Requests\UpdateUser;
use App\Models\User;
use App\Models\UserInfo;
use App\Supports\UserResponse;
use Carbon\Carbon;
use Error;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
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
   
    public function getEmailByToken(Request $request)
    {
        $token = $request->input('token');
        try{
            $decodeToken = Crypt::decryptString($token);
            [$email, $expiryTime] = explode('|', $decodeToken);
    
            $expiryTime = Carbon::parse($expiryTime);
            if ($expiryTime->lt(Carbon::now())) {
                return response([
                    'email' => $email,
                    'message' => 'Đường dẫn đã hết hạn. Vui lòng xác thực lại email.',
                ],400);
            } else {
                return response([
                    'email' => $email,
                ],200);
            }
        }catch(DecryptException $e){
            return response([
                'message' => 'Token invalid',
            ],400);
        }
        
    }
    public function getUser($user){
        $user = User::query()->where('username',$user)->orWhere('id',$user)->first();
        if(!$user) return response(['message' => 'Không tìm thấy user'],404);
        return $user;
    }
    public function store(CreateUser $request)
    {
        try {
            $user = DB::transaction(function () use ($request) {
                $user = User::create([
                    'username' => $request->get('username'),
                    'password' => $request->get('password'),
                    'alias' => $request->get('alias'),
                    'avatar_url' => $request->get('avatar_url'),
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

            return   response()->json([
                'tooken' =>$token,
                'user' => $user
            ]);
        }
        catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ],500);
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

        // Validator::make($request->all(), [
        //     'email' => Rule::unique('user_infos', 'email')->ignore($user->userInfo),
        // ])->validated();

        try {
            $updatedUser = DB::transaction(function () use ($request, $user) {
                $user->update([
                    'alias' => $request->input('alias'),
                    'avatar_url' => $request->input('avatar')
                ]);
                $user->userInfo()->update([
                    'dob' => $request->input('dob'),
                    'description' => $request->input('description'),
                    'gender' =>  $request->input('gender'),
                    'wallpaper' => $request->input('wallpaper')
                ]);

                return $user;
            });

            return response()->json([
                'status' => 200,
                'message' => 'Cập nhật thông tin thành công.',
                'data' =>   $updatedUser->load('userInfo'),
                'avatar' => $request->input('avatar'),
                'wallpaper' => $request->input('wallpaper')
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
