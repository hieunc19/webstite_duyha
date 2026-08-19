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
        Schema::create('procedure_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tên thủ tục / video hướng dẫn
            $table->string('category')->default('residence'); // Lĩnh vực (residence, vneid, civil, land, social, other)
            $table->text('video_url'); // Đường dẫn nhúng (YouTube Embed URL hoặc iframe link)
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedure_videos');
    }
};
