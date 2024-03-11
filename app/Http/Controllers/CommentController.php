<?php

namespace App\Http\Controllers;

use App\Enums\CommentOrder;
use App\Http\Requests\CreateComment;
use App\Http\Requests\GetComment;
use App\Models\Comment;
use App\Models\Post;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CommentController extends Controller
{
    public function getComments(GetComment $request){
        // return  $request->query('order');
        $post = Post::find($request->query('post_id'));
        if(!$post) return response()->json([
            'message' => 'Không tìm thấy bài viết.',
        ],404);
        $order = $request->query('order') ; 
        if($order === 'latest') {
            $comments = $post->comments()->where('parent_id',null)->orderBy('created_at','desc')->with('user.userInfo','childrens.user.userInfo','likes','dislikes')->paginate(5);
        }else {
            $comments = $post->comments()->where('parent_id',null)->orderBy('like','desc')->with(['user.userInfo','childrens.user.userInfo','likes','dislikes'])->paginate(5);
        }

        return response()->json([
            'data' => $comments,   
        ]);
    }
    public function comment(CreateComment $request): JsonResponse
    {
        $user = $request->user();
        $postId = $request->input('post_id');
        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'message' => 'Không tìm thấy bài viết.',
                'status' => 404,
            ], 404);
        }
        try {
            $comment = DB::transaction(function () use ($request, $user, $post) {
                $comment = Comment::create([
                    'content' => $request->content,
                    'post_id' => $post->getKey(),
                    'user_id' => $user->getKey(),
                    'parent_id' => $request->get('parent_id') ?? null,
                    'like' => 0,
                ]);

                return $comment;
            });

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

    public function vote(Request $request, Comment $comment): JsonResponse
    {
        $action = $request->input('action');
        $user = $request->user();
        try {
            return DB::transaction(function () use ($user,  $action , $comment) {
                switch ($action):
                    case (0): 
                        //like
                        $rs = $comment->dislikes()->detach($user->getKey());
                        $rs == 0 ? 1 :2;
                        $comment->likes()->syncWithoutDetaching($user->getKey());
                        $comment->update([
                            'like' => $comment->like +  ($rs === 1 ? 2 : 1 ) ,
                        ]);
                        return response()->json([
                            'status' => 200,
                        ], 200); 
                    ;
                    case 1 :
                        //unlike
                        $comment->likes()->detach($user->getKey());
                        $comment->update([
                            'like' => $comment->like - 1
                        ]);
                        return response()->json([
                            'status' => 200,
                        ], 200); 
                    case 2:
                        //dislike
                        $rs = $comment->likes()->detach($user->getKey());
                        $comment->dislikes()->syncWithoutDetaching($user->getKey());
                        $comment->update([
                            'like' => $comment->like -  ($rs === 1 ? 2 : 1 )
                        ]);
                        return response()->json([
                            'status' => 200,
                        ], 200); 
                    case 3 :
                        $comment->dislikes()->detach($user->getKey());
                        $comment->update([
                            'like' => $comment->like + 1
                        ]);
                        return response()->json([
                            'status' => 200,
                        ], 200); 
                endswitch;
            });

          
        } catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }
    public function delete(Request $request, Comment $comment){
        if($request->user()->id !== $comment->user->id ){
            return response([
                'message'=> 'Bạn không có quyền xóa comment này!',
            ],403);
        }
        Comment::destroy( $comment->childrens->pluck('id'));
        $comment->likes()->detach();
        $comment->dislikes()->detach();
        $comment->delete();
        return response(['message'=> "Xóa thành công"]);
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
