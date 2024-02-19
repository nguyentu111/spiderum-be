<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserFollower;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserFollowerController extends Controller
{
    public function getFollowers(Request $request): JsonResponse {
        $user = $request->user();
        $follwerIds = $user->followers->pluck('source_id');
        $followers = User::query()->select(['id', 'alias'])->whereIn('id', $follwerIds)->get();

        return response()->json([
            'status' => 200,
            'message' => "Lấy thông tin người theo dỗi thành công.",
            'data' => $followers
        ]);
    }

    public function getFollowings(Request $request): JsonResponse
    {
        $user = $request->user();
        $follwerIds = $user->followings->pluck('target_id');
        $followers = User::query()->select(['id', 'alias'])->whereIn('id', $follwerIds)->get();

        return response()->json([
            'status' => 200,
            'message' => "Lấy thông tin người theo dỗi thành công.",
            'data' => $followers
        ]);
    }

    public function follow(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'target_id' => ['required', 'uuid']
        ]);

        $target = User::find($data['target_id']);

        if (!$target) {
            return response()->json([
                'status' => 200,
                'errorMessage' => 'Không tìm thấy thông tin người dùng.'
            ]);
        }

        UserFollower::create([
            'source_id' => $user->getKey(),
            'target_id' => $target->getKey()
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Theo dõi người dùng thành công.',
        ]);
    }

    public function unfollow(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'target_id' => ['required', 'uuid']
        ]);

        $target = User::find($data['target_id']);

        if (!$target) {
            return response()->json([
                'status' => 404,
                'errorMessage' => 'Không tìm thấy thông tin người dùng.'
            ], 404);
        }

        $result = UserFollower::findUserFollower($user->getKey(), $target->getKey())->delete();

        if ($result) {
            return response()->json([
                'status' => 200,
                'message' => 'Hủy theo dõi người dùng thành công.',
            ]);
        }
        else {
            return response()->json([
                'status' => 400,
                'message' => 'Hủy theo dõi người dùng thất bại.',
            ]);
        }
    }
}
