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
        Schema::table('waste_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('waste_schedules', 'collection_days')) {
                $table->json('collection_days')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('waste_schedules', 'collection_days')) {
                $table->dropColumn('collection_days');
            }
        });
    }
};
