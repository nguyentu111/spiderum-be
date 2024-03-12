<?php

namespace App\Http\Controllers;

use App\Enums\SortNewFeedEnum;
use App\Http\Requests\NewfeedRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class NewfeedController extends Controller
{
    public function getNewfeed(NewfeedRequest $request)
    {
        $perPage = $request->per_page;
        switch ($request->sort) {
            case SortNewFeedEnum::Hot->value: {
                $posts = Post::query()->orderBy('view', 'desc')->with(['author','categories','tags'])->paginate($perPage)->withQueryString();

                return response()->json([
                    'message' => 'Lấy danh sách bài viết nổi bật thành công.',
                    'status' => 200,
                    'data' => $posts,
                ], 200);
            }

            case SortNewFeedEnum::New->value: {
                $posts = Post::query()->orderBy('created_at', 'desc')->with(['author','categories','tags'])->paginate($perPage)->withQueryString();

                return response()->json([
                    'message' => 'Lấy danh sách bài viết mới thành công.',
                    'status' => 200,
                    'data' => $posts
                ], 200);
            }

            case SortNewFeedEnum::Follow->value: {
                $user = auth('sanctum')->user();
                if(!$user) return response()->json(['message' => 'unauthenticated'],401);
                $userFollowerIds = $user->followerIds;
                $posts = Post::query()
                    ->whereIn('author_id', $userFollowerIds)
                    ->orderBy('created_at', 'desc')
                    ->with(['author','categories','tags'])
                    ->paginate($perPage)->withQueryString();

                return response()->json([
                    'message' => 'Lấy danh sách bài viết mới thành công.',
                    'status' => 200,
                    'data' => $posts
                ], 200);
            }

            case SortNewFeedEnum::Controversial->value: {
                $posts = Post::query()
                    ->withCount('comments')
                    ->orderBy('comments_count','desc')
                    ->with(['author','categories','tags'])
                    ->paginate($perPage)->withQueryString();

                return response()->json([
                    'message' => 'Lấy danh sách bài viết sôi nổi thành công.',
                    'status' => 200,
                    'data' => $posts
                ], 200);
            }
            case SortNewFeedEnum::Top->value: {
                $posts = Post::query()
                    ->orderBy('like', 'desc')
                    ->with(['author','categories','tags'])
                    ->paginate($perPage)->withQueryString();

                return response()->json([
                    'message' => 'Lấy danh sách bài viết nổi bật thành công.',
                    'status' => 200,
                    'data' => $posts
                ], 200);
            }
            default :{
                return response()->json([
                   'message' => 'no sort'
                ], 400);
            }
        }
    }
    public function getTopView(Request $request){
        $limit = $request->limit ?? 10;
        $posts = Post::orderBy('view', 'desc')->with(['author','categories','tags'])->take($limit)->get();
        return    response()->json([
            'status' => 200,
            'data' => $posts,
        ], 200);
    }
}
