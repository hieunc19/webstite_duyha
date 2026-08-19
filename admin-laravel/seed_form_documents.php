<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\FormDocument;

$initialForms = [
    [
        'code' => 'Mẫu TK-KS',
        'title' => 'Tờ khai đăng ký khai sinh',
        'description' => 'Dùng cho cha, mẹ hoặc người thân thích đi đăng ký khai sinh lần đầu cho trẻ em mới sinh.',
        'category' => 'ho_tich',
        'category_text' => 'Hộ tịch & Tư pháp',
        'agency' => 'Bộ phận Tư pháp - Hộ tịch',
        'fee' => 'Miễn phí',
        'steps' => [
            "Mục (1) Kính gửi: Ghi rõ 'Ủy ban nhân dân phường Duy Hà'.",
            "Mục (2) Người yêu cầu: Ghi đầy đủ họ tên, ngày sinh, số CCCD, nơi cư trú của người đi khai sinh.",
            "Mục (3) Thông tin người được khai sinh: Ghi họ, chữ đệm, tên trẻ bằng chữ in hoa; ngày tháng năm sinh; nơi sinh; quê quán; dân tộc; quốc tịch.",
            "Mục (4) Thông tin Cha & Mẹ: Ghi đầy đủ họ tên, năm sinh, dân tộc, quốc tịch, nơi cư trú của cả cha và mẹ theo CCCD."
        ],
        'docs' => [
            "Giấy chứng sinh (bản chính do cơ sở y tế cấp).",
            "Bản sao hoặc xuất trình CCCD của cha và mẹ.",
            "Giấy chứng nhận kết hôn của cha mẹ (nếu có đăng ký kết hôn)."
        ],
        'notes' => 'Trong thời hạn 60 ngày kể từ ngày sinh con, cha hoặc mẹ có trách nhiệm đăng ký khai sinh cho con.',
        'is_active' => true,
        'sort_order' => 1,
    ],
    [
        'code' => 'Mẫu TK-KH',
        'title' => 'Tờ khai đăng ký kết hôn',
        'description' => 'Dùng cho hai bên nam nữ có nguyện vọng xác lập quan hệ hôn nhân hợp pháp theo Luật Hôn nhân và Gia đình.',
        'category' => 'ho_tich',
        'category_text' => 'Hộ tịch & Tư pháp',
        'agency' => 'Bộ phận Tư pháp - Hộ tịch',
        'fee' => 'Miễn phí',
        'steps' => [
            "Cột 'Bên Nam' & 'Bên Nữ': Khai chi tiết Họ tên, ngày tháng năm sinh, dân tộc, quốc tịch, nghề nghiệp, nơi cư trú.",
            "Số thẻ CCCD/Hộ chiếu: Khai chính xác số, ngày cấp, nơi cấp.",
            "Tình trạng hôn nhân: Ghi rõ 'Chưa đăng ký kết hôn lần nào' hoặc 'Đã ly hôn theo Bản án/Quyết định số...'.",
            "Hai bên nam nữ cùng ký và ghi rõ họ tên ở cuối tờ khai."
        ],
        'docs' => [
            "CCCD gắn chip của cả hai bên nam và nữ.",
            "Giấy xác nhận tình trạng hôn nhân của bên không thường trú tại Phường Duy Hà.",
            "Bản sao Bản án/Quyết định ly hôn có hiệu lực pháp luật (nếu trước đó đã từng kết hôn và ly hôn)."
        ],
        'notes' => 'Cả hai bên nam và nữ phải có mặt trực tiếp tại trụ sở UBND Phường khi làm thủ tục và ký vào Sổ đăng ký kết hôn.',
        'is_active' => true,
        'sort_order' => 2,
    ],
    [
        'code' => 'Mẫu TK-XNHN',
        'title' => 'Tờ khai cấp Giấy xác nhận tình trạng hôn nhân',
        'description' => 'Dùng để xác nhận tình trạng độc thân phục vụ mục đích kết hôn, vay vốn ngân hàng, mua bán chuyển nhượng bất động sản.',
        'category' => 'ho_tich',
        'category_text' => 'Hộ tịch & Tư pháp',
        'agency' => 'Bộ phận Tư pháp - Hộ tịch',
        'fee' => 'Miễn phí',
        'steps' => [
            "Ghi đầy đủ thông tin nhân thân của người yêu cầu cấp giấy.",
            "Mục 'Trong thời gian cư trú tại...': Ghi rõ các khoảng thời gian cư trú từ đủ 18 tuổi đến nay.",
            "Mục 'Mục đích sử dụng': Ghi rõ mục đích như 'Để đăng ký kết hôn với anh/chị...' hoặc 'Để làm thủ tục chuyển nhượng quyền sử dụng đất'."
        ],
        'docs' => [
            "Bản chính CCCD của người yêu cầu.",
            "Trường hợp đã ly hôn thì nộp bản sao Trích lục ghi chú ly hôn hoặc Bản án ly hôn."
        ],
        'notes' => 'Giấy xác nhận tình trạng hôn nhân có giá trị 6 tháng kể từ ngày cấp.',
        'is_active' => true,
        'sort_order' => 3,
    ],
    [
        'code' => 'Mẫu TK-KT',
        'title' => 'Tờ khai đăng ký khai tử',
        'description' => 'Dùng cho thân nhân người đã mất đăng ký khai tử theo quy định của pháp luật.',
        'category' => 'ho_tich',
        'category_text' => 'Hộ tịch & Tư pháp',
        'agency' => 'Bộ phận Tư pháp - Hộ tịch',
        'fee' => 'Miễn phí',
        'steps' => [
            "Khai thông tin người đi khai tử và mối quan hệ với người đã mất.",
            "Khai thông tin người đã mất: Họ tên, năm sinh, nơi cư trú cuối cùng, thời gian chết, địa điểm chết, nguyên nhân chết."
        ],
        'docs' => [
            "Giấy báo tử (do Trạm y tế hoặc Bệnh viện cấp).",
            "CCCD của người đã mất và người đi khai tử."
        ],
        'notes' => 'Thời hạn đăng ký khai tử là trong vòng 15 ngày kể từ ngày có người chết.',
        'is_active' => true,
        'sort_order' => 4,
    ],
    [
        'code' => 'Mẫu 04a/ĐK',
        'title' => 'Đơn đăng ký, cấp Giấy chứng nhận quyền sử dụng đất (Sổ đỏ)',
        'description' => 'Dùng cho hộ gia đình, cá nhân xin cấp Sổ đỏ lần đầu đối với thửa đất đang sử dụng.',
        'category' => 'dat_dai',
        'category_text' => 'Địa chính & Đất đai',
        'agency' => 'Bộ phận Địa chính - Xây dựng',
        'fee' => 'Theo quy định HĐND tỉnh',
        'steps' => [
            "Phần 1: Kê khai thông tin người sử dụng đất (vợ, chồng hoặc đồng sở hữu).",
            "Phần 2: Kê khai chi tiết thửa đất: Thửa đất số, Tờ bản đồ số, Địa chỉ, Diện tích (m²), Mục đích sử dụng, Thời hạn sử dụng, Nguồn gốc sử dụng đất.",
            "Phần 3: Kê khai tài sản gắn liền với đất (nhà ở, công trình xây dựng nếu có)."
        ],
        'docs' => [
            "Giấy tờ về quyền sử dụng đất theo Điều 137 Luật Đất đai 2024.",
            "Trích lục bản đồ địa chính hoặc trích đo địa chính thửa đất.",
            "Chứng từ thực hiện nghĩa vụ tài chính (nếu có)."
        ],
        'notes' => 'Cần lấy xác nhận nguồn gốc sử dụng đất không có tranh chấp từ Tổ trưởng TDP trước khi nộp.',
        'is_active' => true,
        'sort_order' => 5,
    ],
    [
        'code' => 'Mẫu 09/ĐK',
        'title' => 'Đơn đăng ký biến động đất đai, tài sản gắn liền với đất',
        'description' => 'Dùng khi chuyển nhượng, tặng cho, thừa kế, chia tách thửa đất, đổi tên chủ sử dụng đất trên sổ đỏ.',
        'category' => 'dat_dai',
        'category_text' => 'Địa chính & Đất đai',
        'agency' => 'Bộ phận Địa chính - Xây dựng',
        'fee' => 'Theo quy định HĐND tỉnh',
        'steps' => [
            "Khai thông tin chủ sử dụng đất cũ và mới.",
            "Nội dung biến động: Ghi rõ 'Chuyển nhượng quyền sử dụng đất theo Hợp đồng số...' hoặc 'Tặng cho con đẻ...'.",
            "Kê khai cam kết các nghĩa vụ thuế, lệ phí trước bạ."
        ],
        'docs' => [
            "Bản chính Giấy chứng nhận quyền sử dụng đất (Sổ đỏ).",
            "Hợp đồng chuyển nhượng/tặng cho đã được công chứng/chứng thực.",
            "Tờ khai thuế thu nhập cá nhân và lệ phí trước bạ."
        ],
        'notes' => 'Nộp tại Bộ phận Một cửa trong vòng 30 ngày kể từ ngày công chứng hợp đồng.',
        'is_active' => true,
        'sort_order' => 6,
    ],
    [
        'code' => 'Mẫu ĐK-XD',
        'title' => 'Đơn đề nghị cấp phép sửa chữa, cải tạo nhà ở riêng lẻ',
        'description' => 'Dùng khi có nhu cầu sửa chữa, nâng tầng, cải tạo công trình nhà ở làm thay đổi kết cấu chịu lực.',
        'category' => 'dat_dai',
        'category_text' => 'Địa chính & Đất đai',
        'agency' => 'Bộ phận Địa chính - Xây dựng',
        'fee' => '50.000đ - 100.000đ',
        'steps' => [
            "Khai thông tin chủ đầu tư xây dựng.",
            "Địa điểm sửa chữa, hiện trạng công trình trước khi sửa.",
            "Quy mô cải tạo: Diện tích xây dựng tầng 1, tổng diện tích sàn, số tầng nâng thêm, chiều cao công trình."
        ],
        'docs' => [
            "Bản sao Sổ đỏ thửa đất.",
            "Bản vẽ hiện trạng và bản vẽ phương án sửa chữa cải tạo.",
            "Ảnh chụp hiện trạng mặt đứng công trình tiếp giáp đường phố/ngõ xóm."
        ],
        'notes' => 'Đảm bảo khoảng lùi xây dựng và an toàn kết cấu cho các hộ liền kề.',
        'is_active' => true,
        'sort_order' => 7,
    ],
    [
        'code' => 'Mẫu ĐK-NCC',
        'title' => 'Tờ khai thông tin người có công với cách mạng',
        'description' => 'Dùng để rà soát, lập danh sách hưởng chế độ ưu đãi người có công, thân nhân liệt sĩ, thương bệnh binh.',
        'category' => 'chinh_sach',
        'category_text' => 'Lao động - TB & Xã hội',
        'agency' => 'Bộ phận Lao động - Thương binh & Xã hội',
        'fee' => 'Miễn phí',
        'steps' => [
            "Khai đầy đủ thông tin cá nhân của người có công.",
            "Diện đối tượng: Liệt sĩ, Thương binh (tỷ lệ thương tật %), Bệnh binh, Người nhiễm CĐHH, Bà mẹ VNAH...",
            "Số hồ sơ/Quyết định công nhận người có công.",
            "Thông tin tài khoản ngân hàng nhận tiền trợ cấp hàng tháng (nếu có)."
        ],
        'docs' => [
            "Bản sao Quyết định trợ cấp/Bằng Tổ quốc ghi công/Huân huy chương.",
            "Bản sao CCCD của người có công hoặc thân nhân được ủy quyền."
        ],
        'notes' => 'Kê khai chính xác thông tin để phục vụ chi trả an sinh xã hội không dùng tiền mặt.',
        'is_active' => true,
        'sort_order' => 8,
    ],
    [
        'code' => 'Mẫu ĐK-BTXH',
        'title' => 'Tờ khai đề nghị hưởng trợ cấp xã hội hàng tháng',
        'description' => 'Dùng cho người cao tuổi từ đủ 80 tuổi, người khuyết tật nặng/đặc biệt nặng, trẻ em mồ côi.',
        'category' => 'chinh_sach',
        'category_text' => 'Lao động - TB & Xã hội',
        'agency' => 'Bộ phận Lao động - Thương binh & Xã hội',
        'fee' => 'Miễn phí',
        'steps' => [
            "Khai thông tin người đề nghị trợ cấp và người giám hộ (nếu có).",
            "Tình trạng sức khỏe, khả năng tự phục vụ.",
            "Thu nhập và hoàn cảnh gia đình hiện tại."
        ],
        'docs' => [
            "Giấy xác nhận mức độ khuyết tật (đối với người khuyết tật).",
            "Bản sao CCCD và sổ hộ khẩu/xác nhận cư trú."
        ],
        'notes' => 'Hội đồng xét duyệt trợ cấp xã hội Phường Duy Hà họp xét duyệt vào tuần thứ 3 hàng tháng.',
        'is_active' => true,
        'sort_order' => 9,
    ]
];

FormDocument::truncate();
foreach ($initialForms as $form) {
    FormDocument::create($form);
}

echo "Successfully seeded initial FormDocuments!\n";
