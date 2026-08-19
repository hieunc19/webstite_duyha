<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WasteSchedule;

$initialSchedules = [
    [
        'tdp_name' => 'TDP Duy Minh',
        'morning_shift' => '05h30 - 07h00',
        'evening_shift' => '17h00 - 18h30',
        'collection_days' => ['thu_2', 'thu_5'],
        'is_active' => true,
        'sort_order' => 1,
    ],
    [
        'tdp_name' => 'TDP Ngọc Tú',
        'morning_shift' => '06h00 - 07h30',
        'evening_shift' => '17h30 - 19h00',
        'collection_days' => ['thu_3', 'thu_6'],
        'is_active' => true,
        'sort_order' => 2,
    ],
    [
        'tdp_name' => 'TDP Động Linh Trang',
        'morning_shift' => '05h30 - 07h00',
        'evening_shift' => '16h30 - 18h00',
        'collection_days' => ['thu_2', 'thu_5'],
        'is_active' => true,
        'sort_order' => 3,
    ],
    [
        'tdp_name' => 'TDP Chuông',
        'morning_shift' => '06h00 - 07h30',
        'evening_shift' => '17h00 - 18h30',
        'collection_days' => ['thu_4', 'thu_7'],
        'is_active' => true,
        'sort_order' => 4,
    ],
    [
        'tdp_name' => 'TDP Bạch Xá',
        'morning_shift' => '05h30 - 07h00',
        'evening_shift' => '17h30 - 19h00',
        'collection_days' => ['thu_3', 'thu_6'],
        'is_active' => true,
        'sort_order' => 5,
    ],
    [
        'tdp_name' => 'TDP Hoàng Đông',
        'morning_shift' => '06h00 - 07h30',
        'evening_shift' => '18h00 - 19h30',
        'collection_days' => ['thu_2', 'thu_6'],
        'is_active' => true,
        'sort_order' => 6,
    ],
    [
        'tdp_name' => 'TDP Hương Cát',
        'morning_shift' => '05h00 - 06h30',
        'evening_shift' => '16h30 - 18h00',
        'collection_days' => ['thu_3', 'thu_7'],
        'is_active' => true,
        'sort_order' => 7,
    ],
    [
        'tdp_name' => 'TDP Duy Hải',
        'morning_shift' => '06h00 - 07h30',
        'evening_shift' => '17h30 - 19h00',
        'collection_days' => ['thu_4', 'chu_nhat'],
        'is_active' => true,
        'sort_order' => 8,
    ],
    [
        'tdp_name' => 'TDP Ngọc Động',
        'morning_shift' => '05h30 - 07h00',
        'evening_shift' => '17h00 - 18h30',
        'collection_days' => ['thu_2', 'thu_5'],
        'is_active' => true,
        'sort_order' => 9,
    ],
    [
        'tdp_name' => 'TDP Đông Hải',
        'morning_shift' => '05h00 - 06h30',
        'evening_shift' => '16h30 - 18h00',
        'collection_days' => ['thu_3', 'thu_6'],
        'is_active' => true,
        'sort_order' => 10,
    ]
];

WasteSchedule::truncate();
foreach ($initialSchedules as $ws) {
    WasteSchedule::create($ws);
}

echo "Successfully seeded initial 10 TDP waste schedules with collection_days!\n";
