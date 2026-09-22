<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('settings', 'is_visible')) {
                $table->boolean('is_visible')->default(true);
            }
        });
    }

    public function down(): void
    {
        // Keep this field on rollback because older production databases may
        // already contain it despite the historical empty migration.
    }
};
