<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\PaginationRequest;
use App\Models\Post;
use App\Models\Series;
use Exception;
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

        $post = Post::findBySlug($slug)->first();

        if (!$post) {
            return response()->json([
                'errorMessage' => "Không tìm thấy bài viết.",
                'status' => 404
            ], 404);
        }

        if (!$post->is_shown && $user->getKey() !== $post->author_id) {
            return response()->json([
                'message' => "Bài viết đã bị ẩn.",
                'status' => 200
            ], 200);
        }

        $isLiked = in_array($user->getKey(), $post->likes->pluck('user_id'));

        return response()->json([
            'message' => "Lấy bài viết thành công.",
            'status' => 200,
            'data' => [
                'post' => $post,
                'isLiked' => $isLiked,
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

        if (!$post) {
            return response()->json([
                'errorMessage' => "Không tìm thấy bài viết.",
                'status' => 404
            ], 404);
        }

        if ($user->getKey() !== $post->author_id) {
            return response()->json([
                'message' => "Không có quyền thực hiện chức năng này.",
                'status' => 200
            ], 200);
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

        if (!$post) {
            return response()->json([
                'errorMessage' => "Không tìm thấy bài viết.",
                'status' => 404
            ], 404);
        }

        if ($user->getKey() !== $post->author_id) {
            return response()->json([
                'message' => "Không có quyền thực hiện chức năng này.",
                'status' => 200
            ], 200);
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

        if (!$query->first()) {
            return response()->json([
                'errorMessage' => "Không tìm thấy bài viết.",
                'status' => 404
            ], 404);
        }
        try {
            DB::transaction(function () use ($user, $query) {
                $query->likes()->attach($user->getKey());

                $post = $query->first();

                $query->update([
                    'like' => $post->like + 1
                ]);
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

        if (!$query->first()) {
            return response()->json([
                'errorMessage' => "Không tìm thấy bài viết.",
                'status' => 404
            ], 404);
        }
        try {
            DB::transaction(function () use ($user, $query) {
                $query->likes()->detech($user->getKey());

                $post = $query->first();

                $query->update([
                    'like' => $post->like - 1
                ]);
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
        if (!$post) {
            return response()->json([
                'errorMessage' => "Không tìm thấy bài viết.",
                'status' => 404
            ], 404);
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

    public function addToSeries(Request $request, string $slugPost, string $slugSeries): JsonResponse
    {
        $user = $request->user();

        $post = Post::findBySlug($slugPost)->first();
        $seriesQuery =  Series::findBySlug($slugSeries);

        if (!$post) {
            return response()->json([
                'errorMessage' => "Không tìm thấy bài viết.",
                'status' => 404
            ], 404);
        }

        if (!$seriesQuery->first()) {
            return response()->json([
                'errorMessage' => "Không tìm thấy series.",
                'status' => 404
            ], 404);
        }

        if (
            $user->getKey() !== $post->author_id
            || $user->getKey() !== $seriesQuery->first()->author_id
        ) {
            return response()->json([
                'message' => "Không có quyền thực hiện chức năng này.",
                'status' => 200
            ], 200);
        }

        try {
            $seriesQuery->posts()->attach($post->getKey());

            return response()->json([
                'message' => "Thêm bài viết vào series thành công.",
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function removeToSeries(Request $request, string $slugPost, string $slugSeries): JsonResponse
    {
        $user = $request->user();

        $post = Post::findBySlug($slugPost)->first();
        $seriesQuery = Series::findBySlug($slugSeries);

        if (!$post) {
            return response()->json([
                'errorMessage' => "Không tìm thấy bài viết.",
                'status' => 404
            ], 404);
        }

        if (!$seriesQuery->first()) {
            return response()->json([
                'errorMessage' => "Không tìm thấy series.",
                'status' => 404
            ], 404);
        }

        if (
            $user->getKey() !== $post->author_id
            || $user->getKey() !== $seriesQuery->first()->author_id
        ) {
            return response()->json([
                'message' => "Không có quyền thực hiện chức năng này.",
                'status' => 200
            ], 200);
        }

        try {
            $seriesQuery->posts()->detach($post->getKey());

            return response()->json([
                'message' => "Loại bỏ bài viết khỏi series thành công.",
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

        if (!$post) {
            return response()->json([
                'errorMessage' => "Không tìm thấy bài viết.",
                'status' => 404
            ], 404);
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

        if (!$post) {
            return response()->json([
                'errorMessage' => "Không tìm thấy bài viết.",
                'status' => 404
            ], 404);
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
}
