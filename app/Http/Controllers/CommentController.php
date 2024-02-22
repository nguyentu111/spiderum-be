<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateComment;
use App\Models\Comment;
use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function comment(CreateComment $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $post = Post::query()->findBySlug($slug);

        if (!$post->first()) {
            return response()->json([
                'errorMessage' => 'Không tìm thấy bài viết.',
                'status' => 404,
            ], 404);
        }
        try {
            $comment = Comment::create([
                'content' => $request->content,
                'post_id' => $post->getKey(),
                'user_id' => $user->getKey(),
                'parent_id' => $request->get('parent_id') ?? null
            ]);

            return response()->json([
                'message' => 'Bình luận thành công.',
                'status' => 200,
                'data' => $comment
            ], 200);
        }
        catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }
}
