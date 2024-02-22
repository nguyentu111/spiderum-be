<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateTag;
use App\Models\Tag;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Tag::get();

        return response()->json([
            'message' => 'Lấy thông tin tag thành công.',
            'status' => 200,
            'data' => $categories,
        ], 200);
    }

    public function store(CreateTag $request): JsonResponse
    {
        $slug = Str::slug($request->name, '-');

        $slugTag = Tag::where('slug', $slug)->first();
        if ($slugTag) {
            return response()->json([
                'errorMessage' => 'Thể loại đã tồn tại',
                'status' => 200,
            ], 200);
        }

        $category = Tag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
            'category_id' => $request->get('category_id')
        ]);

        return response()->json([
            'message' => 'Thêm tag thành công.',
            'status' => 200,
            'data' => $category,
        ], 200);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'message' => 'Không tìm thấy tag',
                'status' => 404,
            ], 404);
        }
        $name = $request->name;
        $slug = Str::slug($name, '-');

        $existedTag = Tag::where('slug', $slug)->first();
        if ($existedTag && $existedTag->getKey() === $tag->getKey()) {
            return response()->json([
                'errorMessage' => 'Tag đã tồn tại.',
                'status' => 200,
            ], 200);
        }

        try {
            $tag->update([
                'name' => $name,
                'slug' => $slug
            ]);

            return response()->json([
                'message' => 'Cập nhật tag thành công.',
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 500,
                'errorMessage' => $exception->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'message' => 'Không tìm thấy tag',
                'status' => 404,
            ], 404);
        }

        //Check post

        try {
            $tag->delete();

            return response()->json([
                'message' => 'Xóa tag thành công.',
                'status' => 200,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 500,
                'errorMessage' => $exception->getMessage()
            ], 500);
        }
    }
}
