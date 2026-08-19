<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Place;
use App\Models\Neighborhood;
use App\Models\MeritoriousFamily;
use App\Models\Department;
use App\Models\Official;
use App\Models\TdpOfficial;
use App\Models\Setting;
use App\Models\Procedure;
use App\Models\ProcedureVideo;
use App\Models\ProcedureCategory;
use App\Models\Policy;
use App\Models\CelebrationEvent;
use App\Models\WasteSchedule;
use App\Models\FormDocument;
use Illuminate\Support\Facades\Schema;

echo "Exporting live DB data to DatabaseSeeder.php...\n";

// Helper function to extract clean array attributes minus auto-generated IDs and timestamps
function getModelData($modelClass) {
    if (!class_exists($modelClass)) {
        return [];
    }
    try {
        return $modelClass::all()->map(function($item) {
            $attrs = $item->toArray();
            unset($attrs['id'], $attrs['created_at'], $attrs['updated_at']);
            return $attrs;
        })->toArray();
    } catch (\Throwable $e) {
        return [];
    }
}

function exportArrayCode($array) {
    return var_export($array, true);
}

// Extract model data
$placesData = getModelData(Place::class);
$neighborhoodsData = getModelData(Neighborhood::class);
$meritoriousFamiliesData = getModelData(MeritoriousFamily::class);
$departmentsData = getModelData(Department::class);
$officialsData = getModelData(Official::class);
$tdpOfficialsData = getModelData(TdpOfficial::class);
$settingsData = getModelData(Setting::class);
$celebrationEventsData = getModelData(CelebrationEvent::class);
$proceduresData = getModelData(Procedure::class);
$procedureVideosData = getModelData(ProcedureVideo::class);
$procedureCategoriesData = getModelData(ProcedureCategory::class);
$policiesData = getModelData(Policy::class);
$wasteSchedulesData = getModelData(WasteSchedule::class);
$formDocumentsData = getModelData(FormDocument::class);

$seederCode = "<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Place;
use App\Models\AdministrativeUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. Admin Account
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Places
        \$places = " . exportArrayCode($placesData) . ";
        \$duyHaUnit = AdministrativeUnit::where('code', '13336')->first();
        foreach (\$places as \$p) {
            if (\$duyHaUnit) {
                \$p['administrative_unit_id'] = \$duyHaUnit->id;
            }
            Place::updateOrCreate(['name' => \$p['name']], \$p);
        }

        // 3. Neighborhoods
        \$neighborhoods = " . exportArrayCode($neighborhoodsData) . ";
        foreach (\$neighborhoods as \$n) {
            \App\Models\Neighborhood::updateOrCreate(['name' => \$n['name'], 'type' => \$n['type']], \$n);
        }

        // 4. Meritorious Families
        if (Schema::hasTable('meritorious_families')) {
            \App\Models\MeritoriousFamily::truncate();
            \$families = " . exportArrayCode($meritoriousFamiliesData) . ";
            foreach (\$families as \$f) {
                \App\Models\MeritoriousFamily::create(\$f);
            }
        }

        // 5. Departments
        if (Schema::hasTable('departments')) {
            \App\Models\Department::truncate();
            \$departments = " . exportArrayCode($departmentsData) . ";
            foreach (\$departments as \$d) {
                \App\Models\Department::create(\$d);
            }
        }

        // 6. Officials
        if (Schema::hasTable('officials')) {
            \App\Models\Official::truncate();
            \$officials = " . exportArrayCode($officialsData) . ";
            foreach (\$officials as \$o) {
                \App\Models\Official::create(\$o);
            }
        }

        // 7. TDP Officials
        if (Schema::hasTable('tdp_officials')) {
            \App\Models\TdpOfficial::truncate();
            \$tdpOfficials = " . exportArrayCode($tdpOfficialsData) . ";
            foreach (\$tdpOfficials as \$t) {
                \App\Models\TdpOfficial::create(\$t);
            }
        }

        // 8. Settings
        if (Schema::hasTable('settings')) {
            \$settings = " . exportArrayCode($settingsData) . ";
            foreach (\$settings as \$s) {
                \App\Models\Setting::updateOrCreate(['name' => \$s['name']], \$s);
            }
        }

        // 9. Celebration Events
        if (Schema::hasTable('celebration_events')) {
            \App\Models\CelebrationEvent::truncate();
            \$celebrationEvents = " . exportArrayCode($celebrationEventsData) . ";
            foreach (\$celebrationEvents as \$ce) {
                \App\Models\CelebrationEvent::create(\$ce);
            }
        }

        // 10. Procedures
        if (Schema::hasTable('procedures')) {
            \App\Models\Procedure::truncate();
            \$procedures = " . exportArrayCode($proceduresData) . ";
            foreach (\$procedures as \$p) {
                \App\Models\Procedure::create(\$p);
            }
        }

        // 11. Procedure Videos
        if (Schema::hasTable('procedure_videos')) {
            \App\Models\ProcedureVideo::truncate();
            \$procedureVideos = " . exportArrayCode($procedureVideosData) . ";
            foreach (\$procedureVideos as \$vid) {
                \App\Models\ProcedureVideo::create(\$vid);
            }
        }

        // 12. Procedure Shared Categories
        if (Schema::hasTable('procedure_categories')) {
            \App\Models\ProcedureCategory::truncate();
            \$categories = " . exportArrayCode($procedureCategoriesData) . ";
            foreach (\$categories as \$cat) {
                \App\Models\ProcedureCategory::create(\$cat);
            }
        }

        // 13. Policies & Regulations
        if (Schema::hasTable('policies')) {
            \App\Models\Policy::truncate();
            \$policies = " . exportArrayCode($policiesData) . ";
            foreach (\$policies as \$pol) {
                \App\Models\Policy::create(\$pol);
            }
        }

        // 14. Waste Schedules
        if (Schema::hasTable('waste_schedules')) {
            \App\Models\WasteSchedule::truncate();
            \$wasteSchedules = " . exportArrayCode($wasteSchedulesData) . ";
            foreach (\$wasteSchedules as \$ws) {
                \App\Models\WasteSchedule::create(\$ws);
            }
        }

        // 15. Form Documents
        if (Schema::hasTable('form_documents')) {
            \App\Models\FormDocument::truncate();
            \$formDocs = " . exportArrayCode($formDocumentsData) . ";
            foreach (\$formDocs as \$fd) {
                \App\Models\FormDocument::create(\$fd);
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}
";

file_put_contents(__DIR__ . '/database/seeders/DatabaseSeeder.php', $seederCode);
echo "Successfully exported live DB to DatabaseSeeder.php!\n";

// Also run dump_to_json.php to sync client JSONs
require_once __DIR__ . '/dump_to_json.php';
