<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateComment;
use App\Models\Comment;
use App\Models\Post;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function comment(CreateComment $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $post = Post::query()->findBySlug($slug)->first();

        if (!$post) {
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
                'parent_id' => $request->get('parent_id') ?? null,
                'like' => 0,
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

    public function likeComment(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $query = Comment::findBySlug($slug);

        if ($result = $this->checkPostExist($query->first())) {
            return response()->json($result, 404);
        }

        try {
            DB::transaction(function () use ($user, $query) {
                $query->likeComments()->attach($user->getKey());

                $isDisliked = $query->whereHas('dislikeComments', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->getKey());
                })->first();

                if (!$isDisliked) {
                    $comment = $query->first();

                    $query->update([
                        'like' => $comment->like + 1
                    ]);
                }
            });

            return response()->json([
                'message' => "Thích bình luận thành công.",
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function unlikeComment(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $query = Comment::findBySlug($slug);

        if ($result = $this->checkCommentExist($query->first())) {
            return response()->json($result, 404);
        }

        $isDisliked = $query->whereHas('dislikeComments', function (Builder $query) use ($user) {
            $query->where('user_id', $user->getKey());
        })->first();

        if ($isDisliked) {
            return response()->json([
                'message' => 'Hành động bị từ chối, bạn chưa thích bình luận này.',
                'status' => 200,
            ], 200);
        }

        try {
            DB::transaction(function () use ($user, $query, $isDisliked) {
                $query->likeComments()->detech($user->getKey());

                if (!$isDisliked) {
                    $comment = $query->first();

                    $query->update([
                        'like' => $comment->like - 1
                    ]);
                }
            });

            return response()->json([
                'message' => "Bỏ thích bình luận thành công.",
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function dislikeComment(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $query = Comment::findBySlug($slug);

        if ($result = $this->checkPostExist($query->first())) {
            return response()->json($result, 404);
        }

        try {
            DB::transaction(function () use ($user, $query) {
                $query->dislikeComments()->attach($user->getKey());

                $isLiked = $query->whereHas('likeComments', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->getKey());
                })->first();

                if (!$isLiked) {
                    $comment = $query->first();

                    $query->update([
                        'like' => $comment->like - 1
                    ]);
                }
            });

            return response()->json([
                'message' => "Không thích bình luận thành công.",
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function undislikeComment(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $query = Comment::findBySlug($slug);

        if ($result = $this->checkCommentExist($query->first())) {
            return response()->json($result, 404);
        }

        $isLiked = $query->whereHas('likeComments', function (Builder $query) use ($user) {
            $query->where('user_id', $user->getKey());
        })->first();

        if ($isLiked) {
            return response()->json([
                'message' => 'Hành động bị từ chối, bạn chưa dislike bình luận này.',
                'status' => 200,
            ], 200);
        }
        try {
            DB::transaction(function () use ($user, $query, $isLiked) {
                $query->dislikeComments()->detech($user->getKey());

                if (!$isLiked) {
                    $comment = $query->first();

                    $query->update([
                        'like' => $comment->like + 1
                    ]);
                }
            });

            return response()->json([
                'message' => "Bỏ không thích bình luận thành công.",
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    private function checkCommentExist(Comment $comment): array|null
    {
        if (!$comment) {
            return [
                'errorMessage' => "Không tìm thấy bình luận.",
                'status' => 404
            ];
        }

        return null;
    }
}
