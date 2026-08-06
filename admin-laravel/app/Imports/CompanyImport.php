<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\Country;
use App\Services\GeoService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Exception;

class CompanyImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new Exception("File Excel rỗng. Vui lòng kiểm tra lại.");
        }

        // Kiểm tra số lượng cột (Yêu cầu 10 cột)
        $firstRow = $rows->first();
        if (count($firstRow) < 10) {
            throw new Exception("File Excel không hợp lệ. Yêu cầu phải có ít nhất 10 cột. File của bạn hiện có " . count($firstRow) . " cột.");
        }

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 1;

            if ($index === 0 && (str_contains((string)$row[0], 'Tên') || str_contains((string)$row[0], 'Name'))) {
                continue;
            }

            $name = $row[0] ?? null;
            if (empty($name)) continue;

            // Xử lý tìm Quốc gia
            $countryName = $row[1] ?? 'Việt Nam';
            $country = Country::where('name', 'like', '%' . trim($countryName) . '%')->first();
            $countryId = $country ? $country->id : 1; // Mặc định là Việt Nam nếu không tìm thấy

            $coordsRaw = $row[9] ?? null;
            $lat = null;
            $lng = null;

            if (!empty($coordsRaw)) {
                if (str_contains($coordsRaw, ',')) {
                    $parts = explode(',', $coordsRaw);
                    $lat = trim($parts[0]);
                    $lng = trim($parts[1]);

                    if (!is_numeric($lat) || !is_numeric($lng)) {
                        throw new Exception("Lỗi ở dòng {$rowNumber}: Tọa độ không đúng định dạng số. Bạn nhập: '{$coordsRaw}'");
                    }
                } else {
                    throw new Exception("Lỗi ở dòng {$rowNumber}: Cột tọa độ thiếu dấu phẩy. Định dạng đúng: 'Vĩ độ, Kinh độ'");
                }
            }

            $data = [
                'name' => $name,
                'tax_code' => $row[2] ?? null,
                'address' => $row[3] ?? null,
                'phone' => $row[4] ?? null,
                'email' => $row[5] ?? null,
                'website' => $row[6] ?? null,
                'representative' => $row[7] ?? null,
                'industry' => $row[8] ?? null,
                'lat' => $lat,
                'lng' => $lng,
                'country_id' => $countryId,
            ];

            $exists = false;
            if (!empty($data['tax_code'])) {
                $exists = Company::where('tax_code', $data['tax_code'])->exists();
            } else {
                $exists = Company::where('name', $data['name'])
                    ->where('address', $data['address'])
                    ->exists();
            }

            if (!$exists) {
                $adminUnitId = null;
                if (!empty($lat) && !empty($lng)) {
                    $adminUnitId = GeoService::findAdminUnitByCoordinates((float)$lat, (float)$lng);
                }

                Company::create([
                    'name' => $data['name'],
                    'tax_code' => $data['tax_code'],
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'website' => $data['website'],
                    'representative' => $data['representative'],
                    'industry' => $data['industry'],
                    'lat' => $data['lat'],
                    'lng' => $data['lng'],
                    'country_id' => $data['country_id'],
                    'administrative_unit_id' => $adminUnitId,
                    'status' => 'active',
                ]);
            }
        }
    }
}
