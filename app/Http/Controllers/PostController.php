<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\GetPosts;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\VotePost;
use App\Models\Post;
use App\Models\Series;
use App\Models\User;
use App\Supports\ArrayToTree;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function getUserPosts(GetPosts $request): JsonResponse
    {
        $user = User::query()->where('username', $request->input('username'))->first();
        if(!$user) return response(['message' => 'Không tìm thấy người dùng này'],404);
        $posts = null;
        if($request->has('series')){
            $series =  $user->series()->where('slug',$request->input('series'))->first();
            if($series) $posts = $series->posts()->with(['author','categories','series','tags','comments' => fn ($query) => $query->orderBy('created_at','desc' ),'comments.user.userInfo','likes','dislikes'])->paginate();
        
        }
        else $posts =  $user->posts()->with(['author','categories','series','tags','comments' => fn ($query) => $query->orderBy('created_at','desc' ),'comments.user.userInfo','likes','dislikes'])->paginate();
        return response()->json([
            'message' => 'Lấy danh sách bài viết thành công.',
            'status' => 200,
            'data' => $posts
        ]);
    }

    public function getPost(Request $request, string $slug): JsonResponse
    {
        $postQuery = Post::where('slug',$slug)->with(['author','categories','series','tags','comments' => fn ($query) => $query->orderBy('created_at','desc' ),'comments.user.userInfo','likes','dislikes']);
        $post = $postQuery->first();
        if ($result = $this->checkPostExist($post)) {
            return response()->json($result, 404);
        }
        // $comments = new ArrayToTree($post->comments->toArray());
        return  response()->json([
            'message' => "Lấy bài viết thành công.",
            'status' => 200,
            'data' => [
                ...$post->toArray(),
                // 'comments' => $comments->buildTree(),
            ]
        ], 200);


        // if ( $result = $this->checkOwnPost($user, $post)) {
        //     return response()->json($result, 200);
        // }

        // if ($result = $this->checkStatusPost($post)) {
        //     return response()->json($result, 200);
        // }
        // $isLiked = $postQuery->whereHas('likes', function (Builder $query) use ($user) {
        //     $query->where('user_id', $user->getKey());
        // });
        // $isFollowed = $user->whereHas('followings', function (Builder $query) use ($post) {
        //     $query->where('target_id', $post);
        // });
        // return response()->json([
        //     'message' => "Lấy bài viết thành công.",
        //     'status' => 200,
        //     'data' => [
        //         'post' => [
        //             ...$post->toArray(),
        //             'comments' => $comments->buildTree(),
        //         ],
        //         'isLiked' => $isLiked->first() ? true : false,
        //         'isFollowed' => $isFollowed->first() ? true : false
        //     ]
        // ], 200);
    }
    public function getSavePosts(Request $request){
        $user = $request->user();
        $posts = $user->savedPosts()->get();
        return response(['data' => $posts]);
    }
    public function store(CreatePostRequest $request) : JsonResponse
    {
        // return $request->categories;
        $user = $request->user();
        $slug = Str::slug($request->name, '-');
        $existecPost = Post::where('slug',$slug)->first();
        if ($existecPost) {
            return response()->json([
                'message' => "Tên bài viết trùng đã được sử dụng.",
                'status' => 400
            ], 400);
        }
        try {
            // return DB::transaction(function() use ($request,$slug,$user){
                DB::beginTransaction();
                $post = Post::create([
                    'name' => $request->name,
                    'slug' => $slug,
                    'content' => $request->json('content'),
                    'author_id' => $user->getKey(),
                    'is_shown' => 1,
                    'thumbnail' => $request->get('thumbnail'),
                ]);
                if($request->get('series')){
                    $seriesQuery = Series::where('id',$request->get('series'))->first();
                    if(!$seriesQuery) {
                        DB::rollBack();
                        return response()->json([
                            'message' => "Không tìm thấy seriess",
                            'status' => 404
                        ], 404);  
                    } 
                    $seriesQuery->posts()->attach($post->getKey());
                }
                if($request->get('categories')){
                    $post->categories()->attach($request->get('categories'));
                }
                DB::commit();
                return response()->json([
                    'message' => "Lưu bài viết thành công.",
                    'status' => 200,
                    'data' => $post,
                ], 200);
            // });
          
        }
        catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function showPost(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $post = Post::where('slug',$slug)->first();

        if ($result = $this->checkPostExist($post)) {
            return response()->json($result, 404);
        }

        if ($result = $this->checkOwnPost($user, $post)) {
            return response()->json($result, 200);
        }

        try {
            $post->update([
                'is_shown' => true
            ]);

            return response()->json([
                'message' => "Công khai bài viết thành công.",
                'status' => 200,
                'data' => $post
            ], 200);
        }
        catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function hidePost(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $post = Post::where('slug',$slug)->first();

        if ($result = $this->checkPostExist($post)) {
            return response()->json($result, 404);
        }

        if ($result = $this->checkOwnPost($user, $post)) {
            return response()->json($result, 200);
        }

        try {
            $post->update([
                'is_shown' => false
            ]);

            return response()->json([
                'message' => "Ẩn bài viết thành công.",
                'status' => 200,
                'data' => $post
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function vote(VotePost $request, string $slug)
    {
        $user = $request->user();
        $action = $request->input('action');
        $query = Post::where('slug',$slug);
        $post = $query->first();
        if ($result = $this->checkPostExist($query->first())) {
            return response()->json($result, 404);
        }
        if ($result = $this->checkStatusPost($query->first())) {
            return response()->json($result, 404);
        }
        try {
            return DB::transaction(function () use ($user, $query,$action,$post) {
                switch ($action): 
                    case 0 : {
                        //like
                        $post->likes()->syncWithoutDetaching($user->getKey());
                        $post->dislikes()->detach($user->getKey());
                        $post->update([
                            'like' =>  $post->likes()->count() -  $post->dislikes()->count()
                        ]);
                        return response()->json([
                            'message' => "ok",
                            'status' => 200,
                            "action" => 0
                        ], 200); 
                    }
                    case 1: {
                        //unlike and undislike 
                        $post->likes()->detach($user->getKey());
                        $post->dislikes()->detach($user->getKey());
                        $post->update([
                            'like' => $post->likes()->count() -  $post->dislikes()->count()
                        ]);
                        return response()->json([
                            'message' => "ok",
                            'status' => 200,
                            "action" => 1

                        ], 200);
                    }
                    case 2: {
                        //dislike
                            $post->likes()->detach($user->getKey());
                            $post->dislikes()->syncWithoutDetaching($user->getKey());
                            $post->update([
                                'like' => $post->likes()->count() -  $post->dislikes()->count()
                            ]);
                            return response()->json([
                                'message' => "ok",
                                'status' => 200,
                                "action" => 2
                            ], 200);
                    }
                endswitch;

            });

         
        }
        catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

  
    public function countView(Request $request, string $slug): JsonResponse
    {
        $post = Post::where('slug',$slug)->first();

        if ($result = $this->checkPostExist($post)) {
            return response()->json($result, 404);
        }

        if ($result = $this->checkStatusPost($post)) {
            return response()->json($result, 404);
        }
        try {
            Post::where('slug',$slug)->update([
                'view' => $post->view + 1
            ]);

            return response()->json([
                'message' => "Xem bài viết thành công.",
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function savePost(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $post = Post::where('slug',$slug)->first();

        if ($result = $this->checkPostExist($post)) {
            return response()->json($result, 404);
        }

        if ($result = $this->checkStatusPost($post)) {
            return response()->json($result, 404);
        }
        try {
            $user->postSaved()->syncWithoutDetaching($post->getKey());

            return response()->json([
                'message' => "Đánh dấu bài viết thành công.",
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function unsavePost(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $post = Post::where('slug',$slug)->first();

        if ($result = $this->checkPostExist($post)) {
            return response()->json($result, 404);
        }

        if ($result = $this->checkStatusPost($post)) {
            return response()->json($result, 404);
        }
        try {
            $user->postSaved()->detach($post->getKey());

            return response()->json([
                'message' => "Bỏ đánh dấu bài viết thành công.",
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function destroy(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $postQuery = Post::where('slug',$slug);

        if ($result = $this->checkPostExist($postQuery->first())) {
            return response()->json($result, 404);
        }

        try {
            $postQuery->delete();

            return response()->json([
                'message' => "Xóa bài viết thành công.",
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    private function checkPostExist(Post $post): array|null
    {
        if (!$post) {
            return [
                'errorMessage' => "Không tìm thấy bài viết.",
                'status' => 404
            ];
        }

        return null;
    }

    private function checkOwnPost(User $user, Post $post): array|null
    {
        if ($user->getKey() !== $post->author_id) {
            return [
                'message' => "Không có quyền thực hiện chức năng này.",
                'status' => 401
            ];
        }

        return null;
    }

    private function checkStatusPost(Post $post): array|null
    {
        if (!$post->is_shown) {
            return [
                'message' => "Bài viết đã bị ẩn.",
                'status' => 400
            ];
        }

        return null;
    }
}
