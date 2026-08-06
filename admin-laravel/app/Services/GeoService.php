<?php

namespace App\Services;

class GeoService
{
    public static function isPointInPolygon($point, $polygon)
    {
        $vertices = $polygon[0]; // Assuming GeoJSON structure: [ [ [lng, lat], [lng, lat], ... ] ]
        $n = count($vertices);
        $inside = false;
        $px = $point['lng'];
        $py = $point['lat'];

        for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
            $vi = $vertices[$i];
            $vj = $vertices[$j];

            if ((($vi[1] > $py) != ($vj[1] > $py)) &&
                ($px < ($vj[0] - $vi[0]) * ($py - $vi[1]) / ($vj[1] - $vi[1]) + $vi[0])
            ) {
                $inside = !$inside;
            }
        }

        return $inside;
    }

    public static function findAdminUnitByCoordinates($lat, $lng)
    {
        $units = \App\Models\AdministrativeUnit::all();

        foreach ($units as $unit) {
            $boundary = $unit->boundary_data;
            if (!$boundary) continue;

            // Handle both Feature and Geometry
            $geometry = $boundary['geometry'] ?? $boundary;
            $type = $geometry['type'] ?? null;
            $coordinates = $geometry['coordinates'] ?? [];

            if ($type === 'Polygon') {
                if (self::isPointInPolygon(['lat' => $lat, 'lng' => $lng], $coordinates)) {
                    return $unit->id;
                }
            } elseif ($type === 'MultiPolygon') {
                foreach ($coordinates as $polygon) {
                    if (self::isPointInPolygon(['lat' => $lat, 'lng' => $lng], $polygon)) {
                        return $unit->id;
                    }
                }
            }
        }

        return null;
    }
}
