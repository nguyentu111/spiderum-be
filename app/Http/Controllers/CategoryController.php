<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategory;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::with('tags')->get();

        return response()->json([
            'message' => 'Lấy thông tin thể loại thành công.',
            'status' => 200,
            'data' => $categories,
        ], 200);
    }

    public function store(CreateCategory $request): JsonResponse
    {
        $slug = Str::slug($request->name, '-');

        $slugCategory = Category::where('slug', $slug)->first();
        if ($slugCategory) {
            return response()->json([
                'errorMessage' => 'Thể loại đã tồn tại',
                'status' => 200,
            ], 200);
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-')
        ]);

        return response()->json([
            'message' => 'Thêm thể loại thành công.',
            'status' => 200,
            'data' => $category,
        ], 200);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Không tìm thấy thể loại',
                'status' => 404,
            ], 404);
        }
        $name = $request->name;
        $slug = Str::slug($name, '-');

        $slugCategory = Category::where('slug', $slug)->first();
        if ($slugCategory && $slugCategory->getKey() === $category->getKey()) {
            return response()->json([
                'errorMessage' => 'Thể loại đã tồn tại.',
                'status' => 200,
            ], 200);
        }

        try {
            $category->update([
                'name' => $name,
                'slug' => $slug
            ]);

            return response()->json([
                'message' => 'Cập nhật thể loại thành công.',
                'status' => 200,
            ], 200);
        }
        catch (Exception $exception) {
            return response()->json([
                'status' => 500,
                'errorMessage' => $exception->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Không tìm thấy thể loại',
                'status' => 404,
            ], 404);
        }

        //Check post

        try {
            $category->delete();

            return response()->json([
                'message' => 'Xóa thể loại thành công.',
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
