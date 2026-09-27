<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Restore the intended homepage flow:
     * hero -> statistics -> agencies -> utilities/procedures -> footer.
     */
    public function up(): void
    {
        if (! Schema::hasTable('homepage_sections')) {
            return;
        }

        DB::transaction(function (): void {
            $orders = [
                'header_navbar' => 0,
                'hero_banner' => 1,
                'stats_cards' => 2,
                'agencies_grid' => 3,
                'quick_utilities' => 4,
                'procedures_utilities' => 5,
                'hdsd_procedure' => 5,
                'footer_section' => 6,
            ];

            foreach ($orders as $sectionCode => $sortOrder) {
                DB::table('homepage_sections')
                    ->where('section_code', $sectionCode)
                    ->update(['sort_order' => $sortOrder]);
            }
        });
    }

    /**
     * The previous order may have been changed from the admin interface, so it
     * cannot be reconstructed safely during rollback.
     */
    public function down(): void
    {
        // Intentionally left blank.
    }
};
