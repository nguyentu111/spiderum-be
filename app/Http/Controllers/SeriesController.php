<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSeries;
use App\Models\Post;
use App\Models\Series;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use illuminate\Support\Str;

class SeriesController extends Controller
{
    public function index(Request $request): JsonResponse
    {   
        $user = User::where('username',$request->input('username'))->first();
        $series = Series::query()->where('author_id',$user->id)->with('posts.categories','posts.author.userInfo')->get();
        return response()->json([
            'message' => 'Lấy danh sách series thành công.',
            'status' => 200,
            'data' => $series,
        ], 200);
    }
    public function show(Request $request,$slug) : JsonResponse {
       
        $series = Series::query()->where('slug',$slug)->with('posts.categories','posts.author.userInfo')->first();
        if (!$series) {
            return response()->json([
                'message' => 'Không tìm thấy thể loại',
                'status' => 404,
            ], 404);
        }
        return response()->json([
            'status' => 200,
            'data' => $series,
        ]);
    }
    public function store(CreateSeries $request): JsonResponse
    {
        $user = $request->user();

        $slug = Str::slug($request->name, '-').'-'.Str::take( Str::uuid(),10);
        return DB::transaction(function() use ($user,$request,$slug){
            $series = Series::create([
                'name' => $request->name,
                'thumbnail' =>$request->input('thumbnail') ?? 'https://res.cloudinary.com/desegehaa/image/upload/v1709515833/spiderum/default-thumbnail/defaultthumbnail_hyf6tn.jpg',
                'slug' =>   $slug,
                'description' => $request->input('description'),
                'is_showed' => true,
                'author_id' => $user->getKey()
            ]);
            if($request->has('posts')){
                $series->posts()->attach($request->posts);
            }
            return response()->json([
                'message' => 'Thêm series thành công.',
                'status' => 200,
                'data' => $series,
            ], 200);
        });
    
    }

    public function update(CreateSeries $request, string $slug): JsonResponse
    {
        $user = $request->user();
        $series = Series::where('slug', $slug)->first();

        if (!$series) {
            return response()->json([
                'message' => 'Không tìm thấy series.',
                'status' => 404,
            ], 404);
        }

        if ($user->getKey() !== $series->author_id) {
            return response()->json([
                'message' => 'Không có quyền thay đổi series.',
                'status' => 400,
            ], 400);
        }

        $name = $request->name;
        $slug = Str::slug($request->name, '-').'-'.Str::take( Str::uuid(),10);

        try {
            return DB::transaction(function() use ($series,$request,$slug,$name){
                $series->update([
                    'name' =>  $name,
                    'slug' =>$series->name !== $name ? $slug :  $series->slug,
                    'description' => $request->description,
                    'is_showed' => $request->is_showed ?? 1,
                ]);
                if($request->has('posts')){
                    $series->posts()->sync($request->input('posts'));
                }
                return response()->json([
                    'message' => 'Cập nhật series thành công.',
                    'status' => 200,
                    'data' =>   $series
                ], 200);

            });
         
        } catch (Exception $exception) {
            return response()->json([
                'status' => 500,
                'errorMessage' => $exception->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();
        $series = Series::where('slug', $slug)->first();

        if (!$series) {
            return response()->json([
                'message' => 'Không tìm thấy thể loại',
                'status' => 404,
            ], 404);
        }

        if ($user->getKey() === $series->author_id) {
            return response()->json([
                'message' => 'Không có quyền thay đổi series.',
                'status' => 400,
            ], 400);
        }

        //Check post

        try {
            $series->delete();

            return response()->json([
                'message' => 'Xóa series thành công.',
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 500,
                'errorMessage' => $exception->getMessage()
            ], 500);
        }
    }

    public function addPostToSeries(Request $request, string $slugPost, string $slugSeries): JsonResponse
    {
        $user = $request->user();

        $post = Post::findBySlug($slugPost)->first();
        $seriesQuery = Series::findBySlug($slugSeries)->first();

        if (!$post) {
            return response()->json([
                'message' => "Không tìm thấy bài viết",
                'status' => 404
            ], 404);
        }

        if (!$seriesQuery) {
            return response()->json([
                'message' => "Không tìm thấy series.",
                'status' => 404
            ], 404);
        }

        if (
            $user->getKey() !== $post->author_id
            || $user->getKey() !== $seriesQuery->author_id
        ) {
            return response()->json([
                'message' => "Không có quyền thực hiện chức năng này.",
                'status' => 200
            ], 200);
        }

        try {
            $seriesQuery->posts()->sync($post->id);

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

    public function removePostInSeries(Request $request, string $slugPost, string $slugSeries): JsonResponse
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

}
