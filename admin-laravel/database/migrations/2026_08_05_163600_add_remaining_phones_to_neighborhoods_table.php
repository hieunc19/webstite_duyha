<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('neighborhoods', function (Blueprint $table) {
            if (!Schema::hasColumn('neighborhoods', 'nguoi_cao_tuoi_phone')) {
                $table->string('nguoi_cao_tuoi_phone')->nullable();
                $table->string('phu_nu_phone')->nullable();
                $table->string('nong_dan_phone')->nullable();
                $table->string('ccb_phone')->nullable();
                $table->string('doan_thanh_nien_phone')->nullable();
            }
        });

        Schema::table('tdp_officials', function (Blueprint $table) {
            if (!Schema::hasColumn('tdp_officials', 'nguoi_cao_tuoi_phone')) {
                $table->string('nguoi_cao_tuoi_phone')->nullable();
                $table->string('phu_nu_phone')->nullable();
                $table->string('nong_dan_phone')->nullable();
                $table->string('ccb_phone')->nullable();
                $table->string('doan_thanh_nien_phone')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('neighborhoods', function (Blueprint $table) {
            $table->dropColumn([
                'nguoi_cao_tuoi_phone',
                'phu_nu_phone',
                'nong_dan_phone',
                'ccb_phone',
                'doan_thanh_nien_phone',
            ]);
        });

        Schema::table('tdp_officials', function (Blueprint $table) {
            $table->dropColumn([
                'nguoi_cao_tuoi_phone',
                'phu_nu_phone',
                'nong_dan_phone',
                'ccb_phone',
                'doan_thanh_nien_phone',
            ]);
        });
    }
};
