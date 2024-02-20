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
        Schema::create('user_save_series', function (Blueprint $table) {
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->foreignUuid('series_id')->references('id')->on('series');
            $table->primary(['user_id', 'series_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_save_series');
    }
};
