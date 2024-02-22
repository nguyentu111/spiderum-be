<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function comment(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $post = Post::query()->findBySlug($slug);
    }
}
