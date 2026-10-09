<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->string('public_service_url')->nullable()->after('download_url');
        });

        DB::table('policies')
            ->whereNull('public_service_url')
            ->orWhere('public_service_url', '')
            ->update(['public_service_url' => 'https://dichvucong.gov.vn/']);
    }

    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropColumn('public_service_url');
        });
    }
};
