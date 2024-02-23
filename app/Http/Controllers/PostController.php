<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\PaginationRequest;
use App\Models\Post;
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
    public function getUserPosts(PaginationRequest $request): JsonResponse
    {
        $user = $request->user();

        $perPage = $request->get('per_page');
        $posts = Post::where('author_id', $user->getKey())->paginate($perPage);

        return response()->json([
            'message' => 'Lấy danh sách bài viết thành công.',
            'status' => 200,
            'data' => $posts
        ]);
    }

    public function getPost(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $postQuery = Post::findBySlug($slug);
        $post = $postQuery->first();

        if ($result = $this->checkPostExist($post)) {
            return response()->json($result, 404);
        }

        if ($result = $this->checkOwnPost($user, $post)) {
            return response()->json($result, 200);
        }

        if ($result = $this->checkStatusPost($post)) {
            return response()->json($result, 200);
        }

        $isLiked = $postQuery->whereHas('likes', function (Builder $query) use ($user) {
            $query->where('user_id', $user->getKey());
        })->first();

        $comments = new ArrayToTree($post->comments->toArray());

        $isFollowed = $user->whereHas('followings', function (Builder $query) use ($post) {
            $query->where('target_id', $post);
        })->frist();

        return response()->json([
            'message' => "Lấy bài viết thành công.",
            'status' => 200,
            'data' => [
                'post' => [
                    ...$post,
                    'comments' => $comments->buildTree(),
                ],
                'isLiked' => $isLiked ? true : false,
                'isFollowed' => $isFollowed ? true : false
            ]
        ], 200);
    }

    public function store(CreatePostRequest $request): JsonResponse
    {
        $user = $request->user();

        $slug = Str::slug($request->name, '-');

        $existecPost = Post::findBySlug($slug)->first();

        if ($existecPost) {
            return response()->json([
                'errorMessage' => "Tên bài viết trùng đã được sử dụng.",
                'status' => 200
            ], 200);
        }

        try {
            $post = Post::create([
                'name' => $request->name,
                'slug' => $slug,
                'content' => $request->json('content'),
                'author_id' => $user->getKey(),
                'is_shown' => $request->get('is_shown')
            ]);

            return response()->json([
                'message' => "Lưu bài viết thành công.",
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

    public function showPost(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $post = Post::findBySlug($slug)->first();

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

        $post = Post::findBySlug($slug)->first();

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

    public function likePost(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $query = Post::findBySlug($slug);

        if ($result = $this->checkPostExist($query->first())) {
            return response()->json($result, 404);
        }

        if ($result = $this->checkStatusPost($query->first())) {
            return response()->json($result, 404);
        }
        try {
            DB::transaction(function () use ($user, $query) {
                $query->likePosts()->attach($user->getKey());

                $isDisliked = $query->whereHas('dislikePosts', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->getKey());
                })->first();

                if (!$isDisliked) {
                    $post = $query->first();

                    $query->update([
                        'like' => $post->like + 1
                    ]);
                }
            });

            return response()->json([
                'message' => "Thích bài viết thành công.",
                'status' => 200,
            ], 200);
        }
        catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function unlikePost(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $query = Post::findBySlug($slug);

        if ($result = $this->checkPostExist($query->first())) {
            return response()->json($result, 404);
        }

        if ($result = $this->checkStatusPost($query->first())) {
            return response()->json($result, 404);
        }

        $isDisliked = $query->whereHas('dislikePosts', function (Builder $query) use ($user) {
            $query->where('user_id', $user->getKey());
        })->first();

        if ($isDisliked) {
            return response()->json([
                'message' => 'Hành động bị từ chối, bạn chưa thích bài viết này.',
                'status' => 200,
            ], 200);
        }
        try {
            DB::transaction(function () use ($user, $query, $isDisliked) {
                $query->likePosts()->detech($user->getKey());

                if (!$isDisliked) {
                    $post = $query->first();

                    $query->update([
                        'like' => $post->like - 1
                    ]);
                }
            });

            return response()->json([
                'message' => "Bỏ thích bài viết thành công.",
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function dislikePost(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $query = Post::findBySlug($slug);

        if ($result = $this->checkPostExist($query->first())) {
            return response()->json($result, 404);
        }

        if ($result = $this->checkStatusPost($query->first())) {
            return response()->json($result, 404);
        }
        try {
            DB::transaction(function () use ($user, $query) {
                $query->likePosts()->attach($user->getKey());

                $isLiked = $query->whereHas('likePosts', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->getKey());
                })->first();

                if (!$isLiked) {
                    $post = $query->first();

                    $query->update([
                        'like' => $post->like + 1
                    ]);
                }
            });

            return response()->json([
                'message' => "Thích bài viết thành công.",
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function undislikePost(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $query = Post::findBySlug($slug);

        if ($result = $this->checkPostExist($query->first())) {
            return response()->json($result, 404);
        }

        if ($result = $this->checkStatusPost($query->first())) {
            return response()->json($result, 404);
        }

        $isLiked = $query->whereHas('likePosts', function (Builder $query) use ($user) {
            $query->where('user_id', $user->getKey());
        })->first();

        if ($isLiked) {
            return response()->json([
                'message' => 'Hành động bị từ chối, bạn chưa dislike bài viết này.',
                'status' => 200,
            ], 200);
        }
        try {
            DB::transaction(function () use ($user, $query, $isLiked) {
                $query->likePosts()->detech($user->getKey());

                $post = $query->first();

                if (!$isLiked) {
                    $query->update([
                        'like' => $post->like - 1
                    ]);
                }
            });

            return response()->json([
                'message' => "Bỏ thích bài viết thành công.",
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function countView(Request $request, string $slug): JsonResponse
    {
        $post = Post::findBySlug($slug)->first();

        if ($result = $this->checkPostExist($post)) {
            return response()->json($result, 404);
        }

        if ($result = $this->checkStatusPost($post)) {
            return response()->json($result, 404);
        }
        try {
            Post::findBySlug($slug)->update([
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

        $post = Post::findBySlug($slug)->first();

        if ($result = $this->checkPostExist($post)) {
            return response()->json($result, 404);
        }

        if ($result = $this->checkStatusPost($post)) {
            return response()->json($result, 404);
        }
        try {
            $user->postSaved()->attach($post->getKey());

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

        $post = Post::findBySlug($slug)->first();

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

        $postQuery = Post::findBySlug($slug);

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
                'status' => 200
            ];
        }

        return null;
    }

    private function checkStatusPost(Post $post): array|null
    {
        if (!$post->is_shown) {
            return [
                'message' => "Bài viết đã bị ẩn.",
                'status' => 200
            ];
        }

        return null;
    }
}
