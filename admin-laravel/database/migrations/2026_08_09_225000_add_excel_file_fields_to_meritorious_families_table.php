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
        Schema::table('meritorious_families', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('name');
            $table->string('file_name')->nullable()->after('file_path');
            $table->string('file_size')->nullable()->after('file_name');
            $table->text('description')->nullable()->after('file_size');
            $table->string('period_date')->nullable()->after('description');
            
            // Allow old columns to be nullable if not already
            if (Schema::hasColumn('meritorious_families', 'type')) {
                $table->string('type')->nullable()->change();
            }
            if (Schema::hasColumn('meritorious_families', 'celebration_event_id')) {
                $table->unsignedBigInteger('celebration_event_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meritorious_families', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'file_name', 'file_size', 'description', 'period_date']);
        });
    }
};
