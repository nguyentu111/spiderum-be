<?php

namespace App\Http\Controllers;

use App\Enums\SortNewFeedEnum;
use App\Http\Requests\NewfeedRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class NewfeedController extends Controller
{
    public function getNewfeed(NewfeedRequest $request)
    {
        $perPage = $request->per_page;
        $posts = Post::query();
        switch ($request->sort) {
            case SortNewFeedEnum::Hot->value: {
                $posts = $posts->orderBy('view', 'desc');
                break;
            }
            case SortNewFeedEnum::New->value: {
                $posts = $posts->orderBy('created_at', 'desc');
                break;
            }
            case SortNewFeedEnum::Follow->value: {
                $user = auth('sanctum')->user();
                if(!$user) return response()->json(['message' => 'unauthenticated'],401);
                $userFollowerIds = $user->followerIds;
                $posts = $posts
                    ->whereIn('author_id', $userFollowerIds)
                    ->orderBy('created_at', 'desc');
                break;
            }

            case SortNewFeedEnum::Controversial->value: {
                $posts = $posts
                    ->withCount('comments')
                    ->orderBy('comments_count','desc');
                break;
            }
            case SortNewFeedEnum::Top->value: {
                $posts = $posts
                    ->withCount('likes')
                    ->withCount('dislikes')
                    ->orderBy(function ($query) {
                        $query->selectRaw('likes_count - dislikes_count');
                    }, 'desc');
                break;
            }
        }
        if($request->has('category')){
            $category = Category::where('slug',$request->input('category'))->first();
            $posts  = $posts->whereHas('categories',function ($query) use ($category){
                $query->where('category_id', $category->id);
            });
        }
        return response()->json([
            'message' => 'Lấy danh sách bài viết nổi bật thành công.',
            'status' => 200,
            'data' => $posts->paginate($perPage)->withQueryString(),
        ], 200);
    }
    public function getTopView(Request $request){
        $limit = $request->limit ?? 10;
        $posts = Post::orderBy('view', 'desc')->take($limit)->get();
        return    response()->json([
            'status' => 200,
            'data' => $posts,
        ], 200);
    }
    public function getNewTopWriter(Request $request){
        $user = auth('sanctum')->user();
        if($user)
            return response()->json([
                'data' => User::has('posts')->where('id','!=',$user->id)->get()
            ]);
        return response()->json([
            'data' => User::has('posts')->get()
        ]);
    }
    public function getOldButGoldPost(){
        $topPosts =   Post::inRandomOrder()->first();
        return response()->json(["data" => $topPosts]);
    }
}
