<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserFollower;
use Illuminate\Http\Request;

class UserFollowerController extends Controller
{
    public function follow(Request $request) {
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

    public function unfollow(Request $request) {
        $user = $request->user();

        $data = $request->validate([
            'target_id' => ['required', 'uuid']
        ]);

        $target = User::find($data['target_id']);

        if (!$target) {
            return response()->json([
                'status' => 404,
                'errorMessage' => 'Không tìm thấy thông tin người dùng.'
            ]);
        }

        $userFollower = UserFollower::query()
            ->where('target_id', $target->getKey())
            ->where('source_id', $user->getKey())
            ->delete();

        $result = $userFollower->delete();

        if ($result) {
            return response()->json([
                'status' => 200,
                'message' => 'Hủy theo dõi người dùng thành công.',
            ]);
        }

        return response()->json([
            'status' => 400,
            'message' => 'Hủy theo dõi người dùng thất bại.',
        ]);
    }
}
