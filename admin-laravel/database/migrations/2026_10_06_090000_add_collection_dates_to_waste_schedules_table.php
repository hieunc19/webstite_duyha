<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('waste_schedules', function (Blueprint $table) {
            if (! Schema::hasColumn('waste_schedules', 'collection_dates')) {
                $table->json('collection_dates')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('waste_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('waste_schedules', 'collection_dates')) {
                $table->dropColumn('collection_dates');
            }
        });
    }
};
