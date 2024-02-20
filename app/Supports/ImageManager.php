<?php

namespace App\Supports;
use App\Contracts\ImageManagerContract;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;

class ImageManager implements ImageManagerContract{

    private Cloudinary $cloudinary;

    private string $folder;

    public function __construct(string $folder)
    {
        $this->folder = $folder;
    }

    public function uploadImage(string $imagePath): array
    {
        try {
            $res = $this->cloudinary::uploadFile($imagePath, [
                'folder' => $this->folder
            ]);

            $path = $res->getSecurePath();

            return [
                'isSuccess' => true,
                'path' => $path
            ];
        }
        catch (Exception $exception) {
            return [
                'isSuccess' => false,
                'errorMessage' => $exception->getMessage()
            ];
        }
    }

    public function removeImage(string $imageUrl): array
    {
        try {
            $publicId = $this->getPublicIdFromImageUrl($imageUrl);

            $this->cloudinary::destroy($publicId);

            return [
                'isSuccess' => true,
            ];
        } catch (Exception $exception) {
            return [
                'isSuccess' => false,
                'errorMessage' => $exception->getMessage()
            ];
        }
    }

    public function getPublicIdFromImageUrl(string $imageUrl): string
    {
        $fileId = $this->getFileId($imageUrl);
        return $this->folder . $this->removeFileExtension($fileId);
    }

    private function removeFileExtension(string $path): string
    {
        return str_replace($path, '', substr($path, 0,strrpos($path, '.')));
    }

    private function getFileId(string $url): string
    {
        return substr($url, strrpos($url, '/'));
    }
}
