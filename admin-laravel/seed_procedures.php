<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Procedure;

$procedures = [
    [
        'sort_order' => 1,
        'code' => 'Mẫu TK-CT01',
        'title' => 'Tờ khai đăng ký tạm trú trực tuyến',
        'category' => 'residence',
        'category_text' => 'Cư trú & Hộ khẩu',
        'desc' => 'Chuẩn bị hồ sơ -> Nộp hồ sơ trực tuyến qua Cổng DVC Bộ Công an -> Cán bộ kiểm tra và thụ lý -> Cập nhật CSDL Quốc gia -> Trả kết quả.',
        'fee' => 'Miễn phí',
        'agency' => 'Bộ phận Công an Phường',
        'docs' => [
            [
                'name' => "1. Tờ khai thay đổi thông tin cư trú (Mẫu CT01)\n2. Giấy tờ chứng minh chỗ ở hợp pháp (Hợp đồng thuê nhà/Sổ đỏ...)\n3. Bản quét/Ảnh chụp Căn cước công dân hoặc VNeID Mức 2",
                'quantity' => '01 bản chính',
                'file' => null,
            ]
        ],
        'download_url' => 'https://dichvucong.gov.vn',
        'is_active' => true,
    ],
    [
        'sort_order' => 2,
        'code' => 'Mẫu TK-CT05',
        'title' => 'Tờ khai thông báo lưu trú qua đêm',
        'category' => 'residence',
        'category_text' => 'Cư trú & Hộ khẩu',
        'desc' => 'Tiếp nhận thông tin khách -> Ghi nhận Sổ tiếp nhận -> Nộp thông báo qua Cổng DVC / VNeID / Phần mềm ASM -> Hệ thống tự động chuyển Công an Phường.',
        'fee' => 'Miễn phí',
        'agency' => 'Bộ phận Công an Phường',
        'docs' => [
            [
                'name' => "1. Thông tin định danh/CCCD của người lưu trú\n2. Địa chỉ cơ sở/hộ gia đình tiếp nhận lưu trú",
                'quantity' => '01 bản chính',
                'file' => null,
            ]
        ],
        'download_url' => 'https://dichvucong.gov.vn',
        'is_active' => true,
    ],
    [
        'sort_order' => 3,
        'code' => 'Mẫu TK-CT07',
        'title' => 'Tờ khai xin cấp Giấy xác nhận thông tin cư trú',
        'category' => 'residence',
        'category_text' => 'Cư trú & Hộ khẩu',
        'desc' => 'Kê khai tờ khai -> Nộp trực tuyến hoặc tại trụ sở -> Cán bộ đối chiếu CSDL dân cư -> Phê duyệt cấp Giấy xác nhận (Mẫu CT07).',
        'fee' => 'Miễn phí',
        'agency' => 'Bộ phận Công an Phường',
        'docs' => [
            [
                'name' => "1. Tờ khai thay đổi thông tin cư trú CT01\n2. CCCD/VNeID của người yêu cầu xác nhận",
                'quantity' => '01 bản chính',
                'file' => null,
            ]
        ],
        'download_url' => 'https://dichvucong.gov.vn',
        'is_active' => true,
    ],
    [
        'sort_order' => 4,
        'code' => 'Mẫu VNEID-02',
        'title' => 'Đơn kích hoạt Định danh điện tử VNeID Mức 2',
        'category' => 'vneid',
        'category_text' => 'Định danh VNeID',
        'desc' => 'Tải app VNeID -> Đăng ký tài khoản -> Chụp ảnh chân dung kích hoạt Mức 1 -> Đến Công an Phường lấy vân tay kích hoạt Mức 2 -> Nhập mã OTP và sử dụng.',
        'fee' => 'Miễn phí',
        'agency' => 'Công an Phường / Bộ Công an',
        'docs' => [
            [
                'name' => "1. Thẻ Căn cước công dân gắn chip\n2. Số điện thoại di động chính chủ\n3. Giấy tờ cần tích hợp (Bằng lái xe, BHYT, Đăng ký xe...)",
                'quantity' => '01 bản chính',
                'file' => null,
            ]
        ],
        'download_url' => 'https://dichvucong.gov.vn',
        'is_active' => true,
    ],
    [
        'sort_order' => 5,
        'code' => 'Mẫu TK-KS',
        'title' => 'Tờ khai đăng ký khai sinh',
        'category' => 'civil',
        'category_text' => 'Hộ tịch & Tư pháp',
        'desc' => 'Nộp hồ sơ -> Bộ phận Tư pháp kiểm tra -> Đăng ký thông tin vào Sổ hộ tịch -> Cấp Trích lục khai sinh và bản chính.',
        'fee' => 'Miễn phí',
        'agency' => 'Bộ phận Tư pháp - Hộ tịch',
        'docs' => [
            [
                'name' => "1. Giấy chứng sinh do cơ sở y tế cấp\n2. Giấy chứng nhận kết hôn của cha mẹ\n3. CCCD/VNeID của người đi đăng ký khai sinh",
                'quantity' => '01 bản chính',
                'file' => null,
            ]
        ],
        'download_url' => 'https://dichvucong.gov.vn',
        'is_active' => true,
    ],
    [
        'sort_order' => 6,
        'code' => 'Mẫu TK-SY',
        'title' => 'Đơn yêu cầu chứng thực bản sao từ bản chính',
        'category' => 'civil',
        'category_text' => 'Hộ tịch & Tư pháp',
        'desc' => 'Xuất trình bản chính -> Cán bộ đối chiếu bản sao -> Ký chứng thực và đóng dấu -> Trả kết quả.',
        'fee' => '2.000 VNĐ / trang',
        'agency' => 'Bộ phận Tư pháp - Hộ tịch',
        'docs' => [
            [
                'name' => "1. Bản chính văn bản, giấy tờ cần chứng thực\n2. Bản sao photo cần chứng thực (nếu nộp trực tiếp)",
                'quantity' => '01 bản chính',
                'file' => null,
            ]
        ],
        'download_url' => 'https://dichvucong.gov.vn',
        'is_active' => true,
    ],
    [
        'sort_order' => 7,
        'code' => 'Mẫu TK-XNHN',
        'title' => 'Tờ khai cấp Giấy xác nhận tình trạng hôn nhân',
        'category' => 'civil',
        'category_text' => 'Hộ tịch & Tư pháp',
        'desc' => 'Nộp Tờ khai -> Cán bộ xác minh dữ liệu hộ tịch -> Chủ tịch UBND Phường ký ban hành -> Trả kết quả.',
        'fee' => '15.000 VNĐ',
        'agency' => 'Bộ phận Tư pháp - Hộ tịch',
        'docs' => [
            [
                'name' => "1. Tờ khai cấp Giấy xác nhận tình trạng hôn nhân\n2. Trích lục bản án ly hôn (nếu đã từng kết hôn và ly hôn)",
                'quantity' => '01 bản chính',
                'file' => null,
            ]
        ],
        'download_url' => 'https://dichvucong.gov.vn',
        'is_active' => true,
    ],
    [
        'sort_order' => 8,
        'code' => 'Mẫu TK-KH',
        'title' => 'Tờ khai đăng ký kết hôn',
        'category' => 'civil',
        'category_text' => 'Hộ tịch & Tư pháp',
        'desc' => 'Hai bên nam nữ nộp Tờ khai và Giấy XN tình trạng hôn nhân -> Cán bộ thụ lý -> Hai bên ký Giấy chứng nhận kết hôn tại trụ sở UBND Phường.',
        'fee' => 'Miễn phí',
        'agency' => 'Bộ phận Tư pháp - Hộ tịch',
        'docs' => [
            [
                'name' => "1. Tờ khai đăng ký kết hôn\n2. Giấy xác nhận tình trạng hôn nhân của hai bên\n3. CCCD/VNeID của hai bên nam nữ",
                'quantity' => '01 bản chính',
                'file' => null,
            ]
        ],
        'download_url' => 'https://dichvucong.gov.vn',
        'is_active' => true,
    ],
    [
        'sort_order' => 9,
        'code' => 'Mẫu TK-TV',
        'title' => 'Tờ khai báo tạm vắng',
        'category' => 'residence',
        'category_text' => 'Cư trú & Hộ khẩu',
        'desc' => 'Công dân nộp Tờ khai báo tạm vắng -> Công an Phường tiếp nhận -> Cập nhật thông tin tạm vắng vào CSDL Quốc gia.',
        'fee' => 'Miễn phí',
        'agency' => 'Bộ phận Công an Phường',
        'docs' => [
            [
                'name' => "1. Tờ khai khai báo tạm vắng\n2. CCCD/VNeID của người khai báo",
                'quantity' => '01 bản chính',
                'file' => null,
            ]
        ],
        'download_url' => 'https://dichvucong.gov.vn',
        'is_active' => true,
    ],
    [
        'sort_order' => 10,
        'code' => 'Mẫu TK-KT',
        'title' => 'Tờ khai đăng ký khai tử',
        'category' => 'civil',
        'category_text' => 'Hộ tịch & Tư pháp',
        'desc' => 'Thân nhân nộp Giấy báo tử -> Cán bộ Tư pháp thụ lý -> Đăng ký vào Sổ hộ tịch -> Cấp Trích lục khai tử.',
        'fee' => 'Miễn phí',
        'agency' => 'Bộ phận Tư pháp - Hộ tịch',
        'docs' => [
            [
                'name' => "1. Giấy báo tử hoặc giấy tờ thay thế Giấy báo tử\n2. CCCD/VNeID của người đi đăng ký khai tử",
                'quantity' => '01 bản chính',
                'file' => null,
            ]
        ],
        'download_url' => 'https://dichvucong.gov.vn',
        'is_active' => true,
    ],
    [
        'sort_order' => 11,
        'code' => 'Mẫu 04a/ĐK',
        'title' => 'Đơn đăng ký, cấp Giấy chứng nhận quyền sử dụng đất',
        'category' => 'land',
        'category_text' => 'Địa chính & Đất đai',
        'desc' => 'Nộp hồ sơ xin cấp Sổ đỏ -> Bộ phận Địa chính thẩm định thực địa -> Chuyển Văn phòng Đăng ký đất đai -> Trả kết quả Sổ đỏ.',
        'fee' => '50.000 VNĐ',
        'agency' => 'Bộ phận Địa chính - Xây dựng',
        'docs' => [
            [
                'name' => "1. Đơn đề nghị cấp giấy phép xây dựng / cấp Sổ đỏ\n2. Bản sao Giấy chứng nhận quyền sử dụng đất (Sổ đỏ)\n3. Bản vẽ thiết kế xây dựng công trình",
                'quantity' => '01 bản chính',
                'file' => null,
            ]
        ],
        'download_url' => 'https://dichvucong.gov.vn',
        'is_active' => true,
    ],
    [
        'sort_order' => 12,
        'code' => 'Mẫu 09/ĐK',
        'title' => 'Đơn đăng ký biến động đất đai, tài sản gắn liền với đất',
        'category' => 'land',
        'category_text' => 'Địa chính & Đất đai',
        'desc' => 'Nộp hợp đồng chuyển nhượng/thừa kế -> Bộ phận Địa chính kiểm tra nghĩa vụ tài chính -> Cập nhật chỉnh lý biến động Sổ đỏ.',
        'fee' => 'Theo biểu phí đất đai',
        'agency' => 'Bộ phận Địa chính - Xây dựng',
        'docs' => [
            [
                'name' => "1. Hợp đồng chuyển nhượng / tặng cho / thừa kế đã công chứng\n2. Giấy chứng nhận quyền sử dụng đất bản gốc\n3. Tờ khai thuế thu nhập cá nhân & lệ phí trước bạ",
                'quantity' => '01 bản chính',
                'file' => null,
            ]
        ],
        'download_url' => 'https://dichvucong.gov.vn',
        'is_active' => true,
    ],
];

foreach ($procedures as $item) {
    Procedure::updateOrCreate(
        ['code' => $item['code'], 'title' => $item['title']],
        $item
    );
}

echo "Seeded " . count($procedures) . " procedures successfully!\n";
