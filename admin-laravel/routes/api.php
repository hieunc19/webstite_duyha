<?php

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Route::get('/places', function () {
    return Place::where('status', 'active')
        ->get()
        ->map(function ($place) {
            return [
                'id' => $place->id,
                'name' => $place->name,
                'category' => $place->category,
                'status' => $place->status,
                'address' => $place->address,
                'lat' => (float) $place->lat,
                'lng' => (float) $place->lng,
                'image' => $place->image ? (Str::startsWith($place->image, 'http') ? $place->image : url('/api/storage/' . $place->image)) : null,
                'administrative_unit_id' => $place->administrative_unit_id,
                'description' => $place->description,
            ];
        });
});

Route::get('/places/{id}', function (int $id) {
    $place = Place::findOrFail($id);

    return [
        'id' => $place->id,
        'name' => $place->name,
        'category' => $place->category,
        'status' => $place->status,
        'address' => $place->address,
        'lat' => (float) $place->lat,
        'lng' => (float) $place->lng,
        'image' => $place->image ? (Str::startsWith($place->image, 'http') ? $place->image : url('/api/storage/' . $place->image)) : null,
        'administrative_unit_id' => $place->administrative_unit_id,
        'description' => $place->description,
    ];
});


Route::get('/storage/{path}', function (string $path) {
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    $filePath = Storage::disk('public')->path($path);

    return response()->file($filePath, [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, HEAD, OPTIONS',
        'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization, X-Requested-With',
    ]);
})->where('path', '.*');

Route::get('/provinces', function () {
    return \App\Models\Province::orderBy('name')->get(['code', 'name', 'full_name', 'latitude', 'longitude']);
});

Route::get('/provinces/{code}/wards', function (string $code) {
    return \App\Models\AdministrativeUnit::where('province_code', $code)
        ->orderBy('name')
        ->get(['id', 'code', 'name', 'type', 'latitude', 'longitude', 'district_name']);
});

Route::get('/provinces/{code}/boundary', function (string $code) {
    $geojsonBaseDir = base_path('../scratch/vietnamese-provinces-database/json/geojson');
    $directories = glob($geojsonBaseDir . '/' . $code . '_*');
    if (!empty($directories)) {
        $dirPath = $directories[0];
        $dirName = basename($dirPath);
        $filePath = $dirPath . '/' . $dirName . '.geojson';
        if (file_exists($filePath)) {
            return response()->file($filePath, [
                'Content-Type' => 'application/json',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            ]);
        }
    }
    return response()->json(['error' => 'Province boundary not found'], 404);
});


Route::get('/administrative-units/{id}', function (int $id) {
    $unit = \App\Models\AdministrativeUnit::findOrFail($id);
    return [
        'id' => $unit->id,
        'code' => $unit->code,
        'name' => $unit->name,
        'type' => $unit->type,
        'lat' => (float) $unit->latitude,
        'lng' => (float) $unit->longitude,
        'link' => $unit->link,
        'boundary' => $unit->boundary_data,
    ];
});

Route::get('/administrative-units', function () {
    // Trả về danh sách xã của Ninh Bình (37) để tương thích ngược
    return \App\Models\AdministrativeUnit::where('province_code', '37')
        ->get()
        ->map(function ($unit) {
            return [
                'id' => $unit->id,
                'name' => $unit->name,
                'type' => $unit->type,
                'lat' => (float) $unit->latitude,
                'lng' => (float) $unit->longitude,
                'link' => $unit->link,
                'boundary' => $unit->boundary_data,
            ];
        });
});

Route::get('/neighborhoods', function () {
    return \App\Models\Neighborhood::all()
        ->map(function ($n) {
            return [
                'id' => $n->id,
                'name' => $n->name,
                'type' => $n->type,
                'group_code' => $n->group_code,
                'leader_name' => $n->leader_name,
                'leader_phone' => $n->leader_phone,
                'households' => (int) $n->households,
                'people' => (int) $n->people,
                'area_ha' => (float) ($n->area_ha ?? 0),
                'status' => $n->status ?? 'active',
                'bi_thu_name' => $n->bi_thu_name,
                'bi_thu_phone' => $n->bi_thu_phone,
                'to_truong_name' => $n->to_truong_name,
                'to_truong_phone' => $n->to_truong_phone,
                'cskv_name' => $n->cskv_name,
                'cskv_phone' => $n->cskv_phone,
                'mat_tan_name' => $n->mat_tan_name,
                'mat_tan_phone' => $n->mat_tan_phone,
                'nguoi_cao_tuoi' => $n->nguoi_cao_tuoi,
                'nguoi_cao_tuoi_phone' => $n->nguoi_cao_tuoi_phone,
                'phu_nu' => $n->phu_nu,
                'phu_nu_phone' => $n->phu_nu_phone,
                'nong_dan' => $n->nong_dan,
                'nong_dan_phone' => $n->nong_dan_phone,
                'ccb' => $n->ccb,
                'ccb_phone' => $n->ccb_phone,
                'doan_thanh_nien' => $n->doan_thanh_nien,
                'doan_thanh_nien_phone' => $n->doan_thanh_nien_phone,
            ];
        });
});

Route::get('/tdp-officials', function () {
    // Map new TDPs for the 10 official modal rows
    return \App\Models\Neighborhood::where('type', 'new')->get()->map(function ($item, $index) {
        return [
            'tt'                  => $index + 1,
            'tdp'                 => preg_replace('/^(TDP|Tổ dân phố)\s+/ui', '', trim($item->name)),
            'biThuName'           => $item->bi_thu_name ?? '',
            'biThuPhone'          => $item->bi_thu_phone ?? '',
            'toTruongName'        => $item->to_truong_name ?? '',
            'toTruongPhone'       => $item->to_truong_phone ?? '',
            'cskvName'            => $item->cskv_name ?? '',
            'cskvPhone'           => $item->cskv_phone ?? '',
            'matTanName'          => $item->mat_tan_name ?? '',
            'matTanPhone'         => $item->mat_tan_phone ?? '',
            'nguoiCaoTuoi'        => $item->nguoi_cao_tuoi ?? '',
            'nguoiCaoTuoiPhone'   => $item->nguoi_cao_tuoi_phone ?? '',
            'phuNu'               => $item->phu_nu ?? '',
            'phuNuPhone'          => $item->phu_nu_phone ?? '',
            'nongDan'             => $item->nong_dan ?? '',
            'nongDanPhone'        => $item->nong_dan_phone ?? '',
            'ccb'                 => $item->ccb ?? '',
            'ccbPhone'            => $item->ccb_phone ?? '',
            'doanThanhNien'       => $item->doan_thanh_nien ?? '',
            'doanThanhNienPhone'  => $item->doan_thanh_nien_phone ?? '',
        ];
    });
});

Route::get('/settings', function () {
    $cards = \App\Models\Setting::where('group', 'stats')
        ->orderBy('sort_order', 'asc')
        ->get()
        ->map(function ($item) {
            $iconMap = [
                'stat_1' => ['icon' => 'holiday_village', 'bg' => 'bg-blue-500/10', 'color' => 'text-blue-600 dark:text-blue-400'],
                'stat_2' => ['icon' => 'group', 'bg' => 'bg-emerald-500/10', 'color' => 'text-emerald-600 dark:text-emerald-400'],
                'stat_3' => ['icon' => 'diversity_3', 'bg' => 'bg-amber-500/10', 'color' => 'text-amber-600 dark:text-amber-400'],
                'stat_4' => ['icon' => 'map', 'bg' => 'bg-purple-500/10', 'color' => 'text-purple-600 dark:text-purple-400'],
            ];
            $style = $iconMap[$item->key] ?? ['icon' => 'analytics', 'bg' => 'bg-blue-500/10', 'color' => 'text-blue-600'];

            return [
                'key'        => $item->key,
                'name'       => $item->name,
                'value'      => $item->value,
                'label'      => $item->label,
                'sort_order' => (int) $item->sort_order,
                'icon'       => $style['icon'],
                'bg'         => $style['bg'],
                'color'      => $style['color'],
            ];
        });

    $s1 = \App\Models\Setting::where('key', 'stat_1')->first();
    $s2 = \App\Models\Setting::where('key', 'stat_2')->first();
    $s3 = \App\Models\Setting::where('key', 'stat_3')->first();
    $s4 = \App\Models\Setting::where('key', 'stat_4')->first();

    return response()->json([
        'cards'      => $cards,
        'stat_1_val' => $s1 ? $s1->value : '10',
        'stat_1_lbl' => $s1 ? $s1->label : 'Tổng số tổ dân phố',
        'stat_2_val' => $s2 ? $s2->value : '6.767',
        'stat_2_lbl' => $s2 ? $s2->label : 'Tổng số hộ gia đình',
        'stat_3_val' => $s3 ? $s3->value : '23.615',
        'stat_3_lbl' => $s3 ? $s3->label : 'Tổng số nhân khẩu',
        'stat_4_val' => $s4 ? $s4->value : '15,46 km²',
        'stat_4_lbl' => $s4 ? $s4->label : 'Diện tích (1.546,30 ha)',
    ]);
});

Route::get('/homepage-sections', function () {
    return \App\Models\HomepageSection::orderBy('sort_order', 'asc')->get()->map(function ($sec) {
        return [
            'id'              => $sec->id,
            'section_code'    => $sec->section_code,
            'name'            => $sec->name,
            'custom_title'    => $sec->custom_title,
            'custom_subtitle' => $sec->custom_subtitle,
            'is_visible'      => (bool) $sec->is_visible,
            'sort_order'      => (int) $sec->sort_order,
            'settings'        => $sec->settings,
        ];
    });
});

Route::get('/celebration-events/active', function (Request $request) {
    $event = \App\Models\CelebrationEvent::where('is_featured', true)
        ->where('status', 'active')
        ->first();

    if (!$event) {
        $event = \App\Models\CelebrationEvent::where('status', 'active')->first();
    }

    if (!$event) {
        return response()->json(null);
    }

    $families = $event->meritoriousFamilies()
        ->where('status', 'active')
        ->with('neighborhood')
        ->get()
        ->map(function ($f) {
            return [
                'id' => $f->id,
                'name' => $f->name,
                'type' => $f->type,
                'neighborhood_name' => $f->neighborhood ? $f->neighborhood->name : null,
                'address' => $f->address,
                'representative_name' => $f->representative_name,
                'phone' => $f->phone,
                'benefit_details' => $f->benefit_details,
            ];
        });

    return [
        'id' => $event->id,
        'name' => $event->name,
        'month' => (int) $event->month,
        'day' => (int) $event->day,
        'is_featured' => (bool) $event->is_featured,
        'description' => $event->description,
        'families' => $families,
    ];
});

Route::get('/officials', function () {
    return \App\Models\Official::where('status', 'active')
        ->orderBy('id')
        ->get()
        ->map(function ($o) {
            return [
                'id' => $o->id,
                'name' => $o->name,
                'role' => $o->role,
                'phone' => $o->phone,
                'neighborhood_name' => $o->neighborhood_name,
                'avatar_color' => $o->avatar_color,
                'avatar' => $o->avatar,
                'department' => $o->department,
            ];
        });
});

Route::get('/celebration-events', function () {
    return \App\Models\CelebrationEvent::where('status', 'active')
        ->orderBy('month')
        ->orderBy('day')
        ->get()
        ->map(function ($e) {
            return [
                'id' => $e->id,
                'name' => $e->name,
                'month' => (int) $e->month,
                'day' => (int) $e->day,
                'description' => $e->description,
                'is_featured' => (bool) $e->is_featured,
                'status' => $e->status,
            ];
        });
});

Route::get('/meritorious-families', function () {
    return \App\Models\MeritoriousFamily::where('status', 'active')
        ->orderBy('id')
        ->get()
        ->map(function ($f) {
            return [
                'id' => $f->id,
                'name' => $f->name,
                'type' => $f->type,
                'neighborhood_id' => $f->neighborhood_id,
                'address' => $f->address,
                'representative_name' => $f->representative_name,
                'phone' => $f->phone,
                'benefit_details' => $f->benefit_details,
                'celebration_event_id' => (int) $f->celebration_event_id,
                'status' => $f->status,
            ];
        });
});



