<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportAdminUnits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-admin-units';
    protected $description = 'Import administrative units from CSV and GeoJSON boundaries';

    public function handle()
    {
        $csvPath = base_path('../client/public/administrative_units.csv');
        $jsonPath = base_path('../client/public/boundaries.json');

        if (!file_exists($csvPath)) {
            $this->error("CSV file not found: $csvPath");
            return;
        }

        if (!file_exists($jsonPath)) {
            $this->error("JSON file not found: $jsonPath");
            return;
        }

        $boundaries = json_decode(file_get_contents($jsonPath), true);
        $file = fopen($csvPath, 'r');
        
        // Skip BOM if exists
        $bom = fread($file, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            fseek($file, 0);
        }

        $header = fgetcsv($file);
        // header: STT,ID,Tên đơn vị,Loại hình,Quận/Huyện,Latitude,Longitude,Link

        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < 8) continue;

            $name = $row[2];
            $type = $row[3];
            $lat = $row[5];
            $lng = $row[6];
            $link = $row[7];

            $unit = \App\Models\AdministrativeUnit::updateOrCreate(
                ['name' => $name],
                [
                    'type' => $type,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'link' => $link,
                    'boundary_data' => $boundaries[$name] ?? null
                ]
            );

            $count++;
        }

        fclose($file);
        $this->info("Imported $count administrative units.");
    }
}
