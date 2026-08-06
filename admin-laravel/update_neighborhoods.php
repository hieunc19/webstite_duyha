<?php

use Illuminate\Contracts\Console\Kernel;
use App\Models\Neighborhood;

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

// Update all neighborhoods with complete data
$allNeighborhoods = Neighborhood::all();
$updated = 0;

foreach ($allNeighborhoods as $n) {
    $groupData = $officialsByGroup[$n->group_code] ?? [];
    $n->update(array_merge([
        'leader_name' => $groupData['cskv_name'] ?? null,
        'leader_phone' => $groupData['cskv_phone'] ?? null,
    ], $groupData));
    $updated++;
}

echo "=== SUCCESS: Updated {$updated} neighborhoods with full official data ===\n";
