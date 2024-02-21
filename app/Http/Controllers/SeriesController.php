<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSeries;
use App\Models\Post;
use App\Models\Series;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use illuminate\Support\Str;
class SeriesController extends Controller
{
    public function index(): JsonResponse
    {
        $series = Series::get();

        return response()->json([
            'message' => 'Lấy danh sách series thành công.',
            'status' => 200,
            'data' => $series,
        ], 200);
    }

    public function store(CreateSeries $request): JsonResponse
    {
        $user = $request->user();

        $slug = Str::slug($request->name, '-');

        $slugSeries = Series::where('slug', $slug)->first();
        if ($slugSeries) {
            return response()->json([
                'errorMessage' => 'Series đã tồn tại',
                'status' => 200,
            ], 200);
        }

        $seires = Series::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
            'description' => $request->description,
            'is_showed' => $request->is_showed,
            'author_id' => $user->getKey()
        ]);

        return response()->json([
            'message' => 'Thêm series thành công.',
            'status' => 200,
            'data' => $seires,
        ], 200);
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

        if ($user->getKey() === $series->author_id) {
            return response()->json([
                'message' => 'Không có quyền thay đổi series.',
                'status' => 400,
            ], 400);
        }

        $name = $request->name;
        $slug = Str::slug($name, '-');

        $slugSeries = Series::where('slug', $slug)->first();
        if ($slugSeries && $slugSeries->getKey() === $series->getKey()) {
            return response()->json([
                'errorMessage' => 'Series đã tồn tại.',
                'status' => 200,
            ], 200);
        }

        try {
            $series->update([
                'name' => $name,
                'slug' => $slug,
                'description' => $request->description,
                'is_showed' => $request->is_showed,
            ]);

            return response()->json([
                'message' => 'Cập nhật series thành công.',
                'status' => 200,
            ], 200);
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
        $seriesQuery = Series::findBySlug($slugSeries);

        if ($result = $this->checkPostExist($post)) {
            return response()->json($result, 404);
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
