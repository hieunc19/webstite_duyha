<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Province;
use App\Models\AdministrativeUnit;

class ImportNationalUnits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-national-units';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import 34 provinces and all wards boundaries from local repository';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jsonPath = base_path('../scratch/vietnamese-provinces-database/json/simplified_json_generated_data_vn_units.json');
        $geojsonBaseDir = base_path('../scratch/vietnamese-provinces-database/json/geojson');

        if (!file_exists($jsonPath)) {
            $this->error("Không tìm thấy tệp JSON danh mục tại: {$jsonPath}");
            return 1;
        }

        $this->info("Đang sao lưu liên kết hoàn cảnh hiện tại...");
        $cases = Schema::hasTable('charity_cases') ? DB::table('charity_cases')->select('id', 'name', 'administrative_unit_id')->get() : collect();
        $caseToWardMap = [];
        foreach ($cases as $case) {
            if ($case->administrative_unit_id) {
                $wardName = DB::table('administrative_units')->where('id', $case->administrative_unit_id)->value('name');
                if ($wardName) {
                    $caseToWardMap[$case->id] = $wardName;
                }
            }
        }
        $this->info("Đã sao lưu " . count($caseToWardMap) . " liên kết hoàn cảnh.");

        $this->info("Đang xóa dữ liệu địa giới cũ...");
        Schema::disableForeignKeyConstraints();
        Province::truncate();
        AdministrativeUnit::truncate();
        Schema::enableForeignKeyConstraints();

        $this->info("Đang đọc dữ liệu danh mục...");
        $data = json_decode(file_get_contents($jsonPath), true);
        if (!$data) {
            $this->error("Dữ liệu JSON không hợp lệ hoặc rỗng.");
            return 1;
        }

        $totalProvinces = count($data);
        $this->info("Tìm thấy {$totalProvinces} tỉnh thành.");

        foreach ($data as $pIdx => $prov) {
            $provCode = $prov['Code'];
            $provName = $prov['Name'];
            $provFullName = $prov['FullName'];
            $provCodeName = $prov['CodeName'];
            $wards = $prov['Wards'] ?? [];

            $this->info("========================================");
            $this->info("Đang xử lý tỉnh: {$provFullName} (Code: {$provCode}) - Xã/phường: " . count($wards));

            // Quét các xã phường của tỉnh để đọc GeoJSON và tính centroid
            $provWardsData = [];
            $sumLat = 0;
            $sumLng = 0;
            $validCentroidsCount = 0;

            foreach ($wards as $ward) {
                $wardCode = $ward['Code'];
                $wardName = $ward['Name'];
                $wardFullName = $ward['FullName'];
                $wardCodeName = $ward['CodeName'];

                // Đường dẫn file GeoJSON tương ứng
                $geojsonPath = "{$geojsonBaseDir}/{$provCode}_{$provCodeName}/wards/{$wardCode}_{$wardCodeName}.geojson";
                
                $boundaryData = null;
                $lat = null;
                $lng = null;

                if (file_exists($geojsonPath)) {
                    $geojson = json_decode(file_get_contents($geojsonPath), true);
                    if ($geojson && isset($geojson['features'][0])) {
                        $feature = $geojson['features'][0];
                        $boundaryData = [
                            'type' => 'Feature',
                            'properties' => [
                                'name' => $wardName,
                                'gso_id' => $wardCode,
                                'osm_id' => null
                            ],
                            'geometry' => $feature['geometry'] ?? null
                        ];

                        // Tính centroid
                        $centroid = $this->calculateCentroid($feature['geometry'] ?? null);
                        if ($centroid) {
                            $lat = $centroid['lat'];
                            $lng = $centroid['lng'];
                            $sumLat += $lat;
                            $sumLng += $lng;
                            $validCentroidsCount++;
                        }
                    }
                }

                // Loại hình xã/phường
                $type = 'Xã';
                if (str_starts_with($wardFullName, 'Phường')) {
                    $type = 'Phường';
                } elseif (str_starts_with($wardFullName, 'Thị trấn')) {
                    $type = 'Thị trấn';
                }

                // Xác định tên quận/huyện từ các file GeoJSON (tên huyện thường nằm ở properties của feature)
                $districtName = null;
                if ($boundaryData && isset($feature['properties']['district'])) {
                    $districtName = $feature['properties']['district'];
                }

                // Link Google Maps
                $finalLat = $lat ?? 21.028511; // fallback coordinates
                $finalLng = $lng ?? 105.804817;
                $link = "https://www.google.com/maps/place/" . urlencode($wardFullName . ", " . $provFullName . ", Việt Nam") . "/@{$finalLat},{$finalLng},13z";

                $provWardsData[] = [
                    'code' => $wardCode,
                    'name' => $wardName,
                    'type' => $type,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'link' => $link,
                    'boundary_data' => $boundaryData ? json_encode($boundaryData) : null,
                    'province_code' => $provCode,
                    'district_name' => $districtName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Tính trung vị tọa độ tỉnh
            $provLat = $validCentroidsCount > 0 ? $sumLat / $validCentroidsCount : null;
            $provLng = $validCentroidsCount > 0 ? $sumLng / $validCentroidsCount : null;

            // Nạp Tỉnh và các xã phường trong một transaction để tăng tốc độ và bảo mật dữ liệu
            DB::transaction(function() use ($provCode, $provName, $provFullName, $provCodeName, $provLat, $provLng, $provWardsData) {
                Province::create([
                    'code' => $provCode,
                    'name' => $provName,
                    'full_name' => $provFullName,
                    'code_name' => $provCodeName,
                    'latitude' => $provLat,
                    'longitude' => $provLng,
                ]);

                foreach ($provWardsData as $wardData) {
                    DB::table('administrative_units')->insert($wardData);
                }
            });

            $this->info("Đã nạp thành công tỉnh {$provName} và " . count($provWardsData) . " xã phường.");
        }

        // Khôi phục liên kết hoàn cảnh
        $this->info("========================================");
        $this->info("Đang khôi phục liên kết hoàn cảnh...");
        $mappedCount = 0;
        if (Schema::hasTable('charity_cases')) {
            foreach ($caseToWardMap as $caseId => $wardName) {
                // Tìm xã mới có tên trùng khớp
                $newUnit = AdministrativeUnit::where('name', $wardName)->first();

                if ($newUnit) {
                    DB::table('charity_cases')->where('id', $caseId)->update([
                        'administrative_unit_id' => $newUnit->id
                    ]);
                    $mappedCount++;
                } else {
                    $this->warn("Không tìm thấy xã tương ứng cho hoàn cảnh ID: {$caseId} (xã cũ: {$wardName})");
                }
            }
        }
        $this->info("Khôi phục thành công {$mappedCount} / " . count($caseToWardMap) . " liên kết hoàn cảnh.");
        $this->info("Hoàn tất nạp dữ liệu địa giới quốc gia!");
    }

    /**
     * Calculate polygon/multipolygon centroid
     */
    private function calculateCentroid($geometry)
    {
        if (!$geometry) return null;

        $sumLng = 0;
        $sumLat = 0;
        $totalPoints = 0;

        if ($geometry['type'] === 'Polygon') {
            $ring = $geometry['coordinates'][0] ?? [];
            foreach ($ring as $pt) {
                if (count($pt) >= 2) {
                    $sumLng += $pt[0];
                    $sumLat += $pt[1];
                    $totalPoints++;
                }
            }
        } elseif ($geometry['type'] === 'MultiPolygon') {
            foreach ($geometry['coordinates'] as $polygon) {
                $ring = $polygon[0] ?? [];
                foreach ($ring as $pt) {
                    if (count($pt) >= 2) {
                        $sumLng += $pt[0];
                        $sumLat += $pt[1];
                        $totalPoints++;
                    }
                }
            }
        }

        if ($totalPoints > 0) {
            return [
                'lat' => $sumLat / $totalPoints,
                'lng' => $sumLng / $totalPoints
            ];
        }
        return null;
    }
}
