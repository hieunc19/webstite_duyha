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
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable(); // Mã thủ tục (Mẫu TK-CT01)
            $table->string('title'); // Tên thủ tục hành chính
            $table->string('category')->default('civil'); // Mã category (civil, residence, land, vneid, social, tax)
            $table->string('category_text')->default('Hộ tịch & Tư pháp'); // Tên lĩnh vực hiển thị
            $table->text('desc')->nullable(); // Mô tả tóm tắt
            $table->string('fee')->default('Miễn phí'); // Lệ phí
            $table->string('agency')->default('Bộ phận Một cửa UBND Phường'); // Cơ quan thực hiện
            $table->json('docs')->nullable(); // Thành phần hồ sơ (mảng các giấy tờ)
            $table->string('download_url')->nullable(); // Link nộp trực tuyến hoặc tải biểu mẫu
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
        Schema::dropIfExists('procedures');
    }
};
