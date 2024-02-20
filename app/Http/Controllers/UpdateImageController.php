<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateImage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateImageController extends Controller
{
    public function __invoke(UpdateImage $request): JsonResponse
    {
        try {
            $res = Cloudinary::uploadFile($request->file('image')->getRealPath(), [
                'folder' => 'blog-social-media'
            ]);

            $path = $res->getSecurePath();

            return response()->json([
                'message' => 'Upload ảnh thành công.',
                'status' => 200,
                'data' => [
                    'path' => $path
                ],
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
