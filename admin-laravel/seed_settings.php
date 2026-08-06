<?php

use Illuminate\Contracts\Console\Kernel;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

DB::table('settings')->truncate();

$settings = [
    [
        'key' => 'stat_1',
        'name' => 'Thẻ 1: Tổng số Tổ dân phố',
        'value' => '10',
        'label' => 'Tổng số tổ dân phố',
        'group' => 'stats',
        'sort_order' => 1,
    ],
    [
        'key' => 'stat_2',
        'name' => 'Thẻ 2: Tổng số Hộ gia đình',
        'value' => '6.767',
        'label' => 'Tổng số hộ gia đình',
        'group' => 'stats',
        'sort_order' => 2,
    ],
    [
        'key' => 'stat_3',
        'name' => 'Thẻ 3: Tổng số Nhân khẩu',
        'value' => '23.615',
        'label' => 'Tổng số nhân khẩu',
        'group' => 'stats',
        'sort_order' => 3,
    ],
    [
        'key' => 'stat_4',
        'name' => 'Thẻ 4: Diện tích địa bàn',
        'value' => '15,46 km²',
        'label' => 'Diện tích (1.546,30 ha)',
        'group' => 'stats',
        'sort_order' => 4,
    ],
];

foreach ($settings as $data) {
    Setting::create($data);
    echo "SEEDED CARD: {$data['name']} (Order: {$data['sort_order']})\n";
}

echo "=== SUCCESS: All 4 Stat Cards Seeded with sort_order ===\n";
