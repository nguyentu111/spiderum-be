<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('series_posts', function (Blueprint $table) {
            $table->foreignUuid('post_id')->references('id')->on('posts');
            $table->foreignUuid('series_id')->references('id')->on('series');
            $table->primary(['post_id', 'series_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series_posts');
    }
};
