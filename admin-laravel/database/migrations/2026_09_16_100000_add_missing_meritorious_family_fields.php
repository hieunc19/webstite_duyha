<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bring the schema in line with the fields used by the existing data dump.
     *
     * The earlier migration with the same intent was empty, so a fresh install
     * (especially SQLite) could not seed the current meritorious-family data.
     * Each column is added only when it is missing so an existing MySQL install
     * is left untouched.
     */
    public function up(): void
    {
        if (! Schema::hasTable('meritorious_families')) {
            // The migration is shared with installs created from the current
            // migration set, so this table is expected to exist here.
            return;
        }

        Schema::table('meritorious_families', function (Blueprint $table): void {
            if (! Schema::hasColumn('meritorious_families', 'year')) {
                $table->unsignedSmallInteger('year')->nullable();
            }

            if (! Schema::hasColumn('meritorious_families', 'gift_amount')) {
                $table->decimal('gift_amount', 15, 2)->nullable();
            }

            if (! Schema::hasColumn('meritorious_families', 'gift_details')) {
                $table->text('gift_details')->nullable();
            }
        });

    }

    /**
     * Intentionally left as a no-op. These fields may already exist on an
     * older production database even though the old migration was empty;
     * dropping them during rollback could remove real data.
     */
    public function down(): void
    {
        // Preserve existing data on rollback.
    }
};
