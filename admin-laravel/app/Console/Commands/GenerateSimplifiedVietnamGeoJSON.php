<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Province;

class GenerateSimplifiedVietnamGeoJSON extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-simplified-vietnam-geojson {--factor=15 : Rút gọn tọa độ cách quãng mỗi N điểm}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rút gọn ranh giới các tỉnh thành đang hoạt động và gộp lại thành 1 file GeoJSON cho Mini Map';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $factor = (int) $this->option('factor');
        $geojsonBaseDir = base_path('../scratch/vietnamese-provinces-database/json/geojson');
        $outputFile = base_path('../public/vietnam_provinces_simplified.geojson');

        $this->info("Đang quét 34 tỉnh thành từ database...");
        $provinces = Province::orderBy('code')->get();

        if ($provinces->isEmpty()) {
            $this->error("Không tìm thấy tỉnh thành nào trong database. Vui lòng chạy app:import-national-units trước.");
            return 1;
        }

        $this->info("Tìm thấy " . $provinces->count() . " tỉnh thành. Bắt đầu gộp và rút gọn (factor = {$factor})...");

        $features = [];

        foreach ($provinces as $province) {
            $code = $province->code;
            $name = $province->name;

            // Tìm thư mục tỉnh dạng code_*
            $directories = glob($geojsonBaseDir . '/' . $code . '_*');
            if (empty($directories)) {
                $this->warn("Không tìm thấy thư mục địa giới cho tỉnh {$name} (Code: {$code})");
                continue;
            }

            $dirPath = $directories[0];
            $dirName = basename($dirPath);
            $filePath = $dirPath . '/' . $dirName . '.geojson';

            if (!file_exists($filePath)) {
                $this->warn("Không tìm thấy file geojson tại: {$filePath}");
                continue;
            }

            $geojson = json_decode(file_get_contents($filePath), true);
            if (!$geojson || empty($geojson['features'])) {
                $this->warn("File geojson rỗng hoặc không đúng định dạng: {$filePath}");
                continue;
            }

            // Lấy feature đầu tiên đại diện cho tỉnh thành
            $provFeature = $geojson['features'][0];
            $geometry = $provFeature['geometry'] ?? null;

            if ($geometry) {
                $simplifiedGeometry = $this->simplifyGeometry($geometry, $factor);
                
                $features[] = [
                    'type' => 'Feature',
                    'properties' => [
                        'code' => $code,
                        'name' => $name,
                        'full_name' => $province->full_name,
                    ],
                    'geometry' => $simplifiedGeometry
                ];
                $this->info("Đã rút gọn xong tỉnh: {$name}");
            }
        }

        $featureCollection = [
            'type' => 'FeatureCollection',
            'features' => $features
        ];

        file_put_contents($outputFile, json_encode($featureCollection, JSON_UNESCAPED_UNICODE));
        $this->info("========================================");
        $this->info("Đã xuất file GeoJSON rút gọn thành công tại: {$outputFile}");
        $this->info("Kích thước file: " . round(filesize($outputFile) / 1024, 2) . " KB");

        return 0;
    }

    /**
     * Rút gọn hình học của Polygon hoặc MultiPolygon
     */
    private function simplifyGeometry(array $geometry, int $factor): array
    {
        $type = $geometry['type'] ?? '';
        $coords = $geometry['coordinates'] ?? [];
        $simplifiedCoords = [];

        if ($type === 'Polygon') {
            foreach ($coords as $ring) {
                $simplifiedCoords[] = $this->simplifyRing($ring, $factor);
            }
        } elseif ($type === 'MultiPolygon') {
            foreach ($coords as $polygon) {
                $simplifiedPolygon = [];
                foreach ($polygon as $ring) {
                    $simplifiedPolygon[] = $this->simplifyRing($ring, $factor);
                }
                $simplifiedCoords[] = $simplifiedPolygon;
            }
        } else {
            return $geometry;
        }

        return [
            'type' => $type,
            'coordinates' => $simplifiedCoords,
        ];
    }

    /**
     * Rút gọn 1 vòng tọa độ bằng cách lấy mẫu cách quãng
     */
    private function simplifyRing(array $ring, int $factor): array
    {
        $count = count($ring);
        if ($count <= 4) {
            return $ring;
        }

        $simplified = [];
        $simplified[] = $ring[0]; // Giữ điểm đầu

        for ($i = 1; $i < $count - 1; $i++) {
            if ($i % $factor === 0) {
                $simplified[] = $ring[$i];
            }
        }

        $simplified[] = $ring[$count - 1]; // Giữ điểm cuối
        return $simplified;
    }
}
