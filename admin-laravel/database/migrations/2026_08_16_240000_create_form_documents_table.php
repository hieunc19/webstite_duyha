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
        Schema::create('form_documents', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->default('ho_tich');
            $table->string('category_text')->nullable();
            $table->string('agency')->nullable()->default('Bộ phận Tư pháp - Hộ tịch');
            $table->string('fee')->nullable()->default('Miễn phí');
            $table->string('file_path')->nullable();
            $table->string('download_url')->nullable();
            $table->json('steps')->nullable();
            $table->json('docs')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_documents');
    }
};
