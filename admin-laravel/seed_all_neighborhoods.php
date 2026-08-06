<?php

use Illuminate\Contracts\Console\Kernel;
use App\Models\Neighborhood;
use Illuminate\Support\Facades\Schema;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$officialsByGroup = [
    'ngoc-tu' => [
        'bi_thu_name' => 'Trương Thị Lê', 'bi_thu_phone' => '0963.566.121',
        'to_truong_name' => 'Phan Văn Trịnh', 'to_truong_phone' => '0988.475.861',
        'cskv_name' => 'Thiếu tá Nguyễn Minh Tiến', 'cskv_phone' => '0359.290.686',
        'mat_tan_name' => 'Trương Thị Lê', 'mat_tan_phone' => '0963.566.121',
        'nguoi_cao_tuoi' => 'Hà Minh Khoái', 'phu_nu' => 'Dương T.Thanh Thảo',
        'nong_dan' => 'Nguyễn Thị La', 'ccb' => 'Dương Tuấn Anh', 'doan_thanh_nien' => 'Nguyễn Văn Biên',
    ],
    'duy-minh' => [
        'bi_thu_name' => 'Lê Xuân Hiến', 'bi_thu_phone' => '0378.582.168',
        'to_truong_name' => 'Đặng Quang Thiện', 'to_truong_phone' => '',
        'cskv_name' => 'Đại úy Trần Hữu Tiến', 'cskv_phone' => '0986.361.395',
        'mat_tan_name' => 'Lê Xuân Hiến', 'mat_tan_phone' => '0378.582.168',
        'nguoi_cao_tuoi' => 'Bạch Tường Vân', 'phu_nu' => 'Nguyễn T.Thanh Thủy',
        'nong_dan' => 'Đặng Quốc Việt', 'ccb' => 'Vũ Văn Mười', 'doan_thanh_nien' => 'Đỗ Thị Loan',
    ],
    'dong-linh-trang' => [
        'bi_thu_name' => 'Nguyễn T. Minh Thoa', 'bi_thu_phone' => '0984.687.445',
        'to_truong_name' => 'Nguyễn Văn Tỉnh', 'to_truong_phone' => '0946.844.268',
        'cskv_name' => 'Thiếu úy Vũ Văn Hào', 'cskv_phone' => '0796.191.310',
        'mat_tan_name' => 'Nguyễn Tiến Thụy', 'mat_tan_phone' => '0977.293.567',
        'nguoi_cao_tuoi' => 'Kiều Tiến Năng', 'phu_nu' => 'Phạm Thị Khánh',
        'nong_dan' => 'Lý Thị Ngẩn', 'ccb' => 'Nguyễn Tiến Vinh', 'doan_thanh_nien' => 'Trần Thị Diệp',
    ],
    'chuong' => [
        'bi_thu_name' => 'Ngô Bá Tùy', 'bi_thu_phone' => '0985.834.898',
        'to_truong_name' => 'Đinh Viết Lượng', 'to_truong_phone' => '0966.835.154',
        'cskv_name' => 'Đại úy Nguyễn Văn Việt', 'cskv_phone' => '0972.280.538',
        'mat_tan_name' => 'Đỗ Tiến Lạc', 'mat_tan_phone' => '0983.196.969',
        'nguoi_cao_tuoi' => 'Vũ Duy Cương', 'phu_nu' => 'Phạm Thị Oanh',
        'nong_dan' => 'Ngô Duy Lượng', 'ccb' => 'Bùi Hải Triều', 'doan_thanh_nien' => 'Lương Thị Nhâm',
    ],
    'bach-xa' => [
        'bi_thu_name' => 'Đỗ Hồng Tình', 'bi_thu_phone' => '0982.265.239',
        'to_truong_name' => 'Đỗ Đức Dưỡng', 'to_truong_phone' => '0974.345.244',
        'cskv_name' => 'Đại úy Đoàn Văn Chương', 'cskv_phone' => '0911.940.111',
        'mat_tan_name' => 'Đặng Tiến Cường', 'mat_tan_phone' => '0966.453.916',
        'nguoi_cao_tuoi' => 'Phùng Đăng Long', 'phu_nu' => 'Nguyễn T.Thanh Hương',
        'nong_dan' => 'Phạm Văn Trường', 'ccb' => 'Phùng Đăng Cự', 'doan_thanh_nien' => 'Ngô Thanh Trường',
    ],
    'hoang-dong' => [
        'bi_thu_name' => 'Vũ Tuấn Khương', 'bi_thu_phone' => '0946.765.798',
        'to_truong_name' => 'Lê Quốc Tuấn', 'to_truong_phone' => '0912.165.039',
        'cskv_name' => 'Thiếu tá Ngô Vinh Quang', 'cskv_phone' => '0977.597.118',
        'mat_tan_name' => 'Nguyễn Thị Lợi', 'mat_tan_phone' => '0387.588.325',
        'nguoi_cao_tuoi' => 'Vũ Xuân Bình', 'phu_nu' => 'Vũ Thị Dung',
        'nong_dan' => 'Chu Duy Khoa', 'ccb' => 'Lương Văn Trụ', 'doan_thanh_nien' => 'Nguyễn Tuấn Anh',
    ],
    'huong-cat' => [
        'bi_thu_name' => 'Phùng Quốc Trình', 'bi_thu_phone' => '0912.448.375',
        'to_truong_name' => 'Phùng Tiến Độ', 'to_truong_phone' => '0914.902.148',
        'cskv_name' => 'Thượng úy Đinh Xuân Trường', 'cskv_phone' => '0585.288.686',
        'mat_tan_name' => 'Phùng Quốc Trình', 'mat_tan_phone' => '0912.448.375',
        'nguoi_cao_tuoi' => 'Ngô Duy Lượng', 'phu_nu' => 'Nguyễn Thị Xinh',
        'nong_dan' => 'Vũ Văn Đệ', 'ccb' => 'Đỗ Duy Quát', 'doan_thanh_nien' => 'Phùng Ngọc Nam',
    ],
    'duy-hai' => [
        'bi_thu_name' => 'Đào Nhật Tân', 'bi_thu_phone' => '0976.136.938',
        'to_truong_name' => 'Phan Duy Tự', 'to_truong_phone' => '0383.375.836',
        'cskv_name' => 'Thượng úy Đinh Xuân Trường', 'cskv_phone' => '0585.288.686',
        'mat_tan_name' => 'Trần Thị Lương', 'mat_tan_phone' => '0332.822.168',
        'nguoi_cao_tuoi' => 'Nguyễn Duy Khiêm', 'phu_nu' => 'Phạm Thị Dậu',
        'nong_dan' => 'Trần Duy Khiêm', 'ccb' => 'Nguyễn Văn Luận', 'doan_thanh_nien' => 'Đào Tuấn Anh',
    ],
    'ngoc-dong' => [
        'bi_thu_name' => 'Nguyễn Tiến Chỉnh', 'bi_thu_phone' => '0912.457.813',
        'to_truong_name' => 'Bùi Hữu Lịch', 'to_truong_phone' => '0912.234.057',
        'cskv_name' => 'Đại úy Vũ Ngọc Quang', 'cskv_phone' => '0978.530.570',
        'mat_tan_name' => 'Đinh Văn Hải', 'mat_tan_phone' => '0915.664.321',
        'nguoi_cao_tuoi' => 'Đinh Văn Thành', 'phu_nu' => 'Trương Thị Chi',
        'nong_dan' => 'Phạm Văn Hưng', 'ccb' => 'Nguyễn Văn Lực', 'doan_thanh_nien' => 'Bùi Tuấn Vũ',
    ],
    'dong-hai' => [
        'bi_thu_name' => 'Hoàng Văn Hùng', 'bi_thu_phone' => '0983.456.789',
        'to_truong_name' => 'Phạm Văn Đức', 'to_truong_phone' => '0978.123.456',
        'cskv_name' => 'Thiếu tá Nguyễn Văn Tuân', 'cskv_phone' => '0866.697.088',
        'mat_tan_name' => 'Hoàng Văn Hùng', 'mat_tan_phone' => '0983.456.789',
        'nguoi_cao_tuoi' => 'Đỗ Văn Thắng', 'phu_nu' => 'Lê Thị Mai',
        'nong_dan' => 'Trần Văn Bình', 'ccb' => 'Nguyễn Văn Hưng', 'doan_thanh_nien' => 'Phạm Tuấn Đạt',
    ],
];

$allOriginalRecords = [
    // 10 NEW TDPs
    ['name' => 'TDP Duy Minh', 'type' => 'new', 'group_code' => 'duy-minh', 'households' => 560, 'people' => 2185, 'area_ha' => 114.5],
    ['name' => 'TDP Ngọc Tú', 'type' => 'new', 'group_code' => 'ngoc-tu', 'households' => 730, 'people' => 2767, 'area_ha' => 172.5],
    ['name' => 'TDP Động Linh Trang', 'type' => 'new', 'group_code' => 'dong-linh-trang', 'households' => 951, 'people' => 3505, 'area_ha' => 215.8],
    ['name' => 'TDP Chuồng', 'type' => 'new', 'group_code' => 'chuong', 'households' => 712, 'people' => 2690, 'area_ha' => 142.1],
    ['name' => 'TDP Bạch Xá', 'type' => 'new', 'group_code' => 'bach-xa', 'households' => 663, 'people' => 2480, 'area_ha' => 131.0],
    ['name' => 'TDP Hoàng Đông', 'type' => 'new', 'group_code' => 'hoang-dong', 'households' => 770, 'people' => 2890, 'area_ha' => 168.0],
    ['name' => 'TDP Hương Cát', 'type' => 'new', 'group_code' => 'huong-cat', 'households' => 561, 'people' => 2130, 'area_ha' => 120.0],
    ['name' => 'TDP Duy Hải', 'type' => 'new', 'group_code' => 'duy-hai', 'households' => 890, 'people' => 3350, 'area_ha' => 195.0],
    ['name' => 'TDP Ngọc Động', 'type' => 'new', 'group_code' => 'ngoc-dong', 'households' => 796, 'people' => 2806, 'area_ha' => 155.5],
    ['name' => 'TDP Đông Hải', 'type' => 'new', 'group_code' => 'dong-hai', 'households' => 634, 'people' => 2390, 'area_ha' => 138.0],

    // 15 OLD TDPs
    ['name' => 'TDP Tú', 'type' => 'old', 'group_code' => 'ngoc-tu', 'households' => 730, 'people' => 2767, 'area_ha' => 172.5],
    ['name' => 'TDP Duy Minh (Cũ)', 'type' => 'old', 'group_code' => 'duy-minh', 'households' => 560, 'people' => 2185, 'area_ha' => 114.5],
    ['name' => 'TDP Động Linh', 'type' => 'old', 'group_code' => 'dong-linh-trang', 'households' => 520, 'people' => 1920, 'area_ha' => 118.3],
    ['name' => 'TDP Trịnh', 'type' => 'old', 'group_code' => 'dong-linh-trang', 'households' => 431, 'people' => 1585, 'area_ha' => 97.5],
    ['name' => 'TDP Chuồng (Cũ)', 'type' => 'old', 'group_code' => 'chuong', 'households' => 712, 'people' => 2690, 'area_ha' => 142.1],
    ['name' => 'TDP Bạch Xá (Cũ)', 'type' => 'old', 'group_code' => 'bach-xa', 'households' => 663, 'people' => 2480, 'area_ha' => 131.0],
    ['name' => 'TDP An Nhân', 'type' => 'old', 'group_code' => 'hoang-dong', 'households' => 310, 'people' => 1150, 'area_ha' => 65.0],
    ['name' => 'TDP Hoàng Thượng', 'type' => 'old', 'group_code' => 'hoang-dong', 'households' => 240, 'people' => 910, 'area_ha' => 53.0],
    ['name' => 'TDP Hoàng Hạ', 'type' => 'old', 'group_code' => 'hoang-dong', 'households' => 220, 'people' => 830, 'area_ha' => 50.0],
    ['name' => 'TDP Hương Cát (Cũ)', 'type' => 'old', 'group_code' => 'huong-cat', 'households' => 561, 'people' => 2130, 'area_ha' => 120.0],
    ['name' => 'TDP Tam Giáp', 'type' => 'old', 'group_code' => 'duy-hai', 'households' => 450, 'people' => 1700, 'area_ha' => 98.0],
    ['name' => 'TDP Tứ Giáp', 'type' => 'old', 'group_code' => 'duy-hai', 'households' => 440, 'people' => 1650, 'area_ha' => 97.0],
    ['name' => 'TDP Ngọc Thị', 'type' => 'old', 'group_code' => 'ngoc-dong', 'households' => 410, 'people' => 1450, 'area_ha' => 80.0],
    ['name' => 'TDP Động Linh (Ngọc Động)', 'type' => 'old', 'group_code' => 'ngoc-dong', 'households' => 386, 'people' => 1356, 'area_ha' => 75.5],
    ['name' => 'TDP Đông Hải (Cũ)', 'type' => 'old', 'group_code' => 'dong-hai', 'households' => 634, 'people' => 2390, 'area_ha' => 138.0],
];

Schema::disableForeignKeyConstraints();
Neighborhood::truncate();
Schema::enableForeignKeyConstraints();

foreach ($allOriginalRecords as $item) {
    $groupData = $officialsByGroup[$item['group_code']] ?? [];
    Neighborhood::create(array_merge($item, [
        'status' => 'active',
        'leader_name' => $groupData['cskv_name'] ?? null,
        'leader_phone' => $groupData['cskv_phone'] ?? null,
    ], $groupData));
}

echo "=== SUCCESS: Restored 25 full neighborhood records! ===\n";

require_once __DIR__.'/dump_to_json.php';
