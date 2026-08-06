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
        Schema::table('administrative_units', function (Blueprint $table) {
            $table->string('code')->nullable()->unique();
            $table->string('province_code')->nullable();
            $table->string('district_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administrative_units', function (Blueprint $table) {
            $table->dropColumn(['code', 'province_code', 'district_name']);
        });
    }
};
