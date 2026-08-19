<?php

use Illuminate\Contracts\Console\Kernel;
use App\Models\HomepageSection;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

DB::table('homepage_sections')->truncate();

$sections = [
    [
        'section_code' => 'header_navbar',
        'name' => 'Thanh Header & Menu Điều hướng',
        'custom_title' => 'CỔNG TRA CỨU THÔNG TIN',
        'custom_subtitle' => 'Phường Duy Hà — Tỉnh Ninh Bình',
        'is_visible' => true,
        'sort_order' => 0,
        'settings' => [
            'site_logo' => '/logo.jpg',
            'nav_home_label' => 'Trang chủ',
            'nav_home_show' => true,
            'nav_map_label' => 'Bản đồ số Duy Hà',
            'nav_map_show' => true,
            'admin_btn_label' => 'Quản trị',
            'admin_btn_show' => true,
        ],
    ],
    [
        'section_code' => 'hero_banner',
        'name' => 'Banner Hero & Tiêu đề chính',
        'custom_title' => 'CỔNG TRA CỨU THÔNG TIN PHƯỜNG DUY HÀ',
        'custom_subtitle' => '',
        'is_visible' => true,
        'sort_order' => 1,
        'settings' => [
            'logo_doan_url' => '/logo-doan.png',
        ],
    ],
    [
        'section_code' => 'stats_cards',
        'name' => 'Chỉ số Thống kê Địa bàn',
        'custom_title' => 'Chỉ số thống kê địa bàn',
        'custom_subtitle' => 'Tổng quan quy mô 10 tổ dân phố, hộ gia đình, nhân khẩu và diện tích',
        'is_visible' => true,
        'sort_order' => 2,
    ],
    [
        'section_code' => 'agencies_grid',
        'name' => 'Cơ quan Hành chính & Công trình',
        'custom_title' => 'Danh sách cơ quan hành chính',
        'custom_subtitle' => 'Tra cứu vị trí, thông tin liên hệ và hình ảnh 360° các trụ sở cơ quan công quyền',
        'is_visible' => true,
        'sort_order' => 3,
    ],
    [
        'section_code' => 'procedures_utilities',
        'name' => 'Hướng dẫn Thủ tục & Tiện ích trực tuyến',
        'custom_title' => 'Hướng dẫn thủ tục hành chính & Tiện ích trực tuyến',
        'custom_subtitle' => 'Quy trình thực hiện TTHC trực tuyến và 6 tiện ích tra cứu nhanh',
        'is_visible' => true,
        'sort_order' => 4,
    ],
];

foreach ($sections as $data) {
    $sec = HomepageSection::create($data);
    echo "SEEDED SECTION: {$sec->name} (Order: {$sec->sort_order})\n";
}

echo "=== SUCCESS: All " . count($sections) . " Homepage Sections Seeded ===\n";
