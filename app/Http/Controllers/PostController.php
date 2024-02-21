<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginationRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
