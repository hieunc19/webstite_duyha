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
        Schema::create('tdp_officials', function (Blueprint $table) {
            $table->id();
            $table->string('tdp_name');
            $table->string('bi_thu_name')->nullable();
            $table->string('bi_thu_phone')->nullable();
            $table->string('to_truong_name')->nullable();
            $table->string('to_truong_phone')->nullable();
            $table->string('mat_tan_name')->nullable();
            $table->string('mat_tan_phone')->nullable();
            $table->string('nguoi_cao_tuoi')->nullable();
            $table->string('phu_nu')->nullable();
            $table->string('nong_dan')->nullable();
            $table->string('ccb')->nullable();
            $table->string('doan_thanh_nien')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tdp_officials');
    }
};
