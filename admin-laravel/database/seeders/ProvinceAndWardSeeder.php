<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\AdministrativeUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceAndWardSeeder extends Seeder
{
    /**
     * Copy provinces and administrative_units data from the old database
     * (historical_relic_map) into the new database (philanthropy_map).
     */
    public function run(): void
    {
        $oldDb = 'historical_relic_map';

        // 1. Copy provinces
        $this->command->info("Reading provinces from {$oldDb}...");

        $provinces = DB::select("SELECT * FROM {$oldDb}.provinces");

        if (empty($provinces)) {
            $this->command->error("No provinces found in {$oldDb}.provinces. Aborting.");
            return;
        }

        foreach ($provinces as $p) {
            Province::updateOrCreate(
                ['code' => $p->code],
                [
                    'name'      => $p->name,
                    'full_name' => $p->full_name,
                    'code_name' => $p->code_name,
                    'latitude'  => $p->latitude,
                    'longitude' => $p->longitude,
                ]
            );
        }

        $this->command->info('Imported ' . count($provinces) . ' provinces.');

        // 2. Copy administrative_units (wards/communes)
        $this->command->info("Reading administrative_units from {$oldDb}...");

        $units = DB::select("SELECT * FROM {$oldDb}.administrative_units");

        if (empty($units)) {
            $this->command->warn("No administrative_units found in {$oldDb}. Skipping wards.");
            return;
        }

        foreach ($units as $u) {
            AdministrativeUnit::updateOrCreate(
                ['code' => $u->code],
                [
                    'name'          => $u->name,
                    'type'          => $u->type,
                    'latitude'      => $u->latitude,
                    'longitude'     => $u->longitude,
                    'link'          => $u->link ?? null,
                    'boundary_data' => $u->boundary_data ?? null,
                    'province_code' => $u->province_code ?? null,
                    'district_name' => $u->district_name ?? null,
                ]
            );
        }

        $this->command->info('Imported ' . count($units) . ' administrative units.');
        $this->command->info('Done!');
    }
}
