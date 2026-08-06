<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('neighborhoods', function (Blueprint $table) {
            if (!Schema::hasColumn('neighborhoods', 'bi_thu_name')) {
                $table->string('bi_thu_name')->nullable();
                $table->string('bi_thu_phone')->nullable();
                $table->string('to_truong_name')->nullable();
                $table->string('to_truong_phone')->nullable();
                $table->string('cskv_name')->nullable();
                $table->string('cskv_phone')->nullable();
                $table->string('mat_tan_name')->nullable();
                $table->string('mat_tan_phone')->nullable();
                $table->string('nguoi_cao_tuoi')->nullable();
                $table->string('phu_nu')->nullable();
                $table->string('nong_dan')->nullable();
                $table->string('ccb')->nullable();
                $table->string('doan_thanh_nien')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('neighborhoods', function (Blueprint $table) {
            $table->dropColumn([
                'bi_thu_name',
                'bi_thu_phone',
                'to_truong_name',
                'to_truong_phone',
                'cskv_name',
                'cskv_phone',
                'mat_tan_name',
                'mat_tan_phone',
                'nguoi_cao_tuoi',
                'phu_nu',
                'nong_dan',
                'ccb',
                'doan_thanh_nien',
            ]);
        });
    }
};
