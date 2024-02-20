<?php

namespace App\Http\Controllers;

use App\Contracts\ImageManagerContract;
use App\Http\Requests\UpdateImage;
use Exception;
use Illuminate\Http\JsonResponse;

class UploadImageController extends Controller
{
    public function __invoke(UpdateImage $request, ImageManagerContract $imageManager): JsonResponse
    {
        try {
            $result = $imageManager->uploadImage($request->file('image')->getRealPath());

            if ($result['isSuccess']) {
                return response()->json([
                    'message' => 'Upload ảnh thành công.',
                    'status' => 200,
                    'data' => [
                        'path' => $result['path']
                    ],
                ], 200);
            }
            else {
                return response()->json([
                    'errorMessage' => $result['errorMessage'],
                    'status' => 500,
                ], 500);
            }
        }
        catch (Exception $exception) {
            return response()->json([
                'errorMessage' => $exception->getMessage(),
                'status' => 500,
            ], 500);
        }
    }
}
