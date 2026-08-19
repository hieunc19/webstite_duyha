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
        Schema::create('waste_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('tdp_name');
            $table->string('morning_shift')->nullable()->default('05h30 - 07h00');
            $table->string('evening_shift')->nullable()->default('17h00 - 18h30');
            $table->string('saturday_recycle')->nullable()->default('08h00 - 11h00');
            $table->text('main_routes')->nullable();
            $table->text('collection_point')->nullable();
            $table->string('responsible_unit')->nullable()->default('Đội vệ sinh môi trường Phường Duy Hà');
            $table->string('contact_phone')->nullable();
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
        Schema::dropIfExists('waste_schedules');
    }
};
