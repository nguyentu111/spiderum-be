<?php

namespace App\Contracts;

interface ImageManagerContract {
    public function uploadImage(string $imagePath): array;

    public function removeImage(string $imageUrl): array;

    public function getPublicIdFromImageUrl(string $imageUrl): string;

    private function removeFileExtension(string $path): string;

    private function getFileId(string $url): string;
}
