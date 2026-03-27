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
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('title')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('format')->default('mp4');
            $table->string('quality')->default('best');
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->integer('progress')->default(0);
            $table->string('file_path')->nullable();
            $table->string('file_size')->nullable();
            $table->text('error_message')->nullable();
            $table->boolean('is_playlist')->default(false);
            $table->integer('playlist_count')->default(0);
            $table->integer('playlist_current')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};
