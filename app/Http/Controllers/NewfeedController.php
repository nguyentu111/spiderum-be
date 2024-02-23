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
        $perPage = $request->get('per_page');

        switch ($request->sort) {
            case SortNewFeedEnum::Hot: {
                $posts = Post::query()->orderBy('view', 'DECS')->paginate($perPage);

                return response()->json([
                    'message' => 'Lấy danh sách bài viết nổi bật thành công.',
                    'status' => 200,
                    'data' => $posts
                ], 200);
            }

            case SortNewFeedEnum::New: {
                $posts = Post::query()->orderBy('created_at', 'DECS')->paginate($perPage);

                return response()->json([
                    'message' => 'Lấy danh sách bài viết mới thành công.',
                    'status' => 200,
                    'data' => $posts
                ], 200);
            }

            case SortNewFeedEnum::Follow: {
                $user = $request->user();
                $userFollowerIds = $user->followerIds;

                $posts = Post::query()
                    ->whereIn('author_id', $userFollowerIds)
                    ->orderBy('created_at', 'DECS')
                    ->paginate($perPage);

                return response()->json([
                    'message' => 'Lấy danh sách bài viết mới thành công.',
                    'status' => 200,
                    'data' => $posts
                ], 200);
            }

            case SortNewFeedEnum::Controversial: {

                break;
            }
            case SortNewFeedEnum::Top: {

                break;
            }
        }
    }
}
