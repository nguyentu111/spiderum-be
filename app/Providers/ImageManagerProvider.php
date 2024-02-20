<?php

namespace App\Providers;

use App\Contracts\ImageManagerContract;
use App\Supports\ImageManager;
use Illuminate\Support\ServiceProvider;

class ImageManagerProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ImageManagerContract::class, function () {
            return new ImageManager('blog-social-media');
        });
    }

    public function boot(): void
    {

    }
}
