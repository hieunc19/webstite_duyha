<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

// Define target directory
$mainTargetDir = __DIR__ . '/../client/src/data';

if (!is_dir($mainTargetDir)) {
    mkdir($mainTargetDir, 0755, true);
}

function saveJsonBoth($filename, $data, $mainTargetDir) {
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($mainTargetDir . '/' . $filename, $json);
}

// 1. Provinces
echo "Dumping provinces...\n";
$provinces = \App\Models\Province::all()->map(function($p) {
    return [
        'code' => $p->code,
        'name' => $p->name,
        'full_name' => $p->full_name,
        'latitude' => (float)$p->latitude,
        'longitude' => (float)$p->longitude
    ];
});
saveJsonBoth('provinces.json', $provinces, $mainTargetDir);

// 2. Wards (Administrative Units)
echo "Dumping administrative units...\n";
$wards = \App\Models\AdministrativeUnit::all()->map(function($u) {
    return [
        'id' => $u->id,
        'code' => $u->code,
        'name' => $u->name,
        'type' => $u->type,
        'latitude' => (float)$u->latitude,
        'longitude' => (float)$u->longitude,
        'link' => $u->link,
        'boundary' => $u->boundary_data,
        'province_code' => $u->province_code,
        'district_name' => $u->district_name
    ];
});
saveJsonBoth('wards.json', $wards, $mainTargetDir);

// 3. Places
echo "Dumping places...\n";
$places = \App\Models\Place::all()->map(function($place) {
    return [
        'id' => $place->id,
        'name' => $place->name,
        'category' => $place->category,
        'status' => $place->status,
        'address' => $place->address,
        'lat' => (float)$place->lat,
        'lng' => (float)$place->lng,
        'image' => $place->image,
        'administrative_unit_id' => $place->administrative_unit_id,
        'description' => $place->description
    ];
});
saveJsonBoth('places.json', $places, $mainTargetDir);

// 4. Neighborhoods
echo "Dumping neighborhoods...\n";
$neighborhoods = \App\Models\Neighborhood::all()->map(function($n) {
    return [
        'id' => $n->id,
        'name' => $n->name,
        'type' => $n->type,
        'group_code' => $n->group_code,
        'leader_name' => $n->leader_name,
        'leader_phone' => $n->leader_phone,
        'households' => (int)$n->households,
        'people' => (int)$n->people,
        'area_ha' => (float)($n->area_ha ?? 0),
        'status' => $n->status
    ];
});
saveJsonBoth('neighborhoods.json', $neighborhoods, $mainTargetDir);

// 5. Celebration Events
echo "Dumping celebration events...\n";
$events = \App\Models\CelebrationEvent::all()->map(function($e) {
    return [
        'id' => $e->id,
        'name' => $e->name,
        'month' => (int)$e->month,
        'day' => (int)$e->day,
        'description' => $e->description,
        'is_featured' => (bool)$e->is_featured,
        'status' => $e->status
    ];
});
saveJsonBoth('celebration_events.json', $events, $mainTargetDir);

// 6. Meritorious Families
echo "Dumping meritorious families...\n";
$families = \App\Models\MeritoriousFamily::all()->map(function($f) {
    return [
        'id' => $f->id,
        'name' => $f->name,
        'type' => $f->type,
        'neighborhood_id' => $f->neighborhood_id,
        'address' => $f->address,
        'representative_name' => $f->representative_name,
        'phone' => $f->phone,
        'benefit_details' => $f->benefit_details,
        'celebration_event_id' => $f->celebration_event_id,
        'status' => $f->status
    ];
});
saveJsonBoth('meritorious_families.json', $families, $mainTargetDir);

// 7. Officials
echo "Dumping officials...\n";
$officials = \App\Models\Official::all()->map(function($o) {
    return [
        'id' => $o->id,
        'name' => $o->name,
        'role' => $o->role,
        'phone' => $o->phone,
        'neighborhood_name' => $o->neighborhood_name,
        'avatar_color' => $o->avatar_color,
        'avatar' => $o->avatar,
        'department' => $o->department,
        'status' => $o->status
    ];
});
saveJsonBoth('officials.json', $officials, $mainTargetDir);

// 8. TDP Officials (Cadres)
echo "Dumping TDP officials...\n";
$newNeighborhoods = \App\Models\Neighborhood::where('type', 'new')->get();
$tdpOfficials = $newNeighborhoods->map(function($o, $idx) {
    return [
        'tt' => $idx + 1,
        'tdp' => $o->name,
        'biThuName' => $o->bi_thu_name ?? '',
        'biThuPhone' => $o->bi_thu_phone ?? '',
        'toTruongName' => $o->to_truong_name ?? '',
        'toTruongPhone' => $o->to_truong_phone ?? '',
        'cskvName' => $o->cskv_name ?? '',
        'cskvPhone' => $o->cskv_phone ?? '',
        'matTanName' => $o->mat_tan_name ?? '',
        'matTanPhone' => $o->mat_tan_phone ?? '',
        'nguoiCaoTuoi' => $o->nguoi_cao_tuoi ?? '',
        'nguoiCaoTuoiPhone' => $o->nguoi_cao_tuoi_phone ?? '',
        'phuNu' => $o->phu_nu ?? '',
        'phuNuPhone' => $o->phu_nu_phone ?? '',
        'nongDan' => $o->nong_dan ?? '',
        'nongDanPhone' => $o->nong_dan_phone ?? '',
        'ccb' => $o->ccb ?? '',
        'ccbPhone' => $o->ccb_phone ?? '',
        'doanThanhNien' => $o->doan_thanh_nien ?? '',
        'doanThanhNienPhone' => $o->doan_thanh_nien_phone ?? ''
    ];
});
saveJsonBoth('tdp_officials.json', $tdpOfficials, $mainTargetDir);

// 9. Settings
echo "Dumping settings...\n";
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

$settingsData = [
    'cards'      => $cards->toArray(),
    'stat_1_val' => $s1 ? $s1->value : '10',
    'stat_1_lbl' => $s1 ? $s1->label : 'Tổng số tổ dân phố',
    'stat_2_val' => $s2 ? $s2->value : '6.767',
    'stat_2_lbl' => $s2 ? $s2->label : 'Tổng số hộ gia đình',
    'stat_3_val' => $s3 ? $s3->value : '23.615',
    'stat_3_lbl' => $s3 ? $s3->label : 'Tổng số nhân khẩu',
    'stat_4_val' => $s4 ? $s4->value : '15,46 km²',
    'stat_4_lbl' => $s4 ? $s4->label : 'Diện tích (1.546,30 ha)',
];
saveJsonBoth('settings.json', $settingsData, $mainTargetDir);

// 10. Homepage Sections (Layout Builder)
echo "Dumping homepage sections...\n";
$homepageSections = \App\Models\HomepageSection::orderBy('sort_order', 'asc')->get()->map(function($sec) {
    return [
        'id' => $sec->id,
        'section_code' => $sec->section_code,
        'name' => $sec->name,
        'custom_title' => $sec->custom_title,
        'custom_subtitle' => $sec->custom_subtitle,
        'is_visible' => (bool)$sec->is_visible,
        'sort_order' => (int)$sec->sort_order,
        'settings' => $sec->settings
    ];
});
saveJsonBoth('homepage_sections.json', $homepageSections, $mainTargetDir);

echo "All data dumped successfully to client/src/data!\n";
