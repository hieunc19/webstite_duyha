<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Place;
use App\Models\AdministrativeUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tạo tài khoản Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Định nghĩa danh sách địa điểm mẫu tại Phường Duy Hà, Ninh Bình (Mã đơn vị: 13336)
        $places = [
            [
                'name' => 'UBND Phường Duy Hà',
                'category' => 'government',
                'address' => 'Đường Lê Thái Tổ, Phường Duy Hà, Tỉnh Ninh Bình',
                'lat' => 20.6478448,
                'lng' => 105.914737,
                'description' => 'Trụ sở làm việc chính thức của Ủy ban nhân dân và Hội đồng nhân dân phường Duy Hà, nơi tiếp nhận và giải quyết các thủ tục hành chính, dịch vụ công cộng cho người dân trên địa bàn.',
                'image' => 'https://images.unsplash.com/photo-1577086664693-894d8405334a?q=80&w=600&auto=format&fit=crop',
            ],
            [
                'name' => 'Trụ sở Công an Phường Duy Hà',
                'category' => 'police',
                'address' => 'Đường Lê Thái Tổ, Phường Duy Hà, Tỉnh Ninh Bình',
                'lat' => 20.647900,
                'lng' => 105.913500,
                'description' => 'Cơ quan công an cấp cơ sở thực hiện chức năng bảo vệ an ninh quốc gia, giữ gìn trật tự an toàn xã hội, quản lý cư trú và hỗ trợ phòng chống tội phạm trên địa bàn phường Duy Hà.',
                'image' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=600&auto=format&fit=crop',
            ],

            [
                'name' => 'Trường Tiểu học Duy Hà',
                'category' => 'school',
                'address' => 'Ngõ 12, Đường Lê Thái Tổ, Phường Duy Hà, Tỉnh Ninh Bình',
                'lat' => 20.650000,
                'lng' => 105.913000,
                'description' => 'Trường tiểu học công lập đạt chuẩn quốc gia cấp độ 1, với cơ sở vật chất khang trang, đáp ứng nhu cầu giáo dục tiểu học chất lượng cao cho con em trong khu vực.',
                'image' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?q=80&w=600&auto=format&fit=crop',
            ],
            [
                'name' => 'Trường THCS Duy Hà',
                'category' => 'school',
                'address' => 'Đường Nguyễn Huệ, Phường Duy Hà, Tỉnh Ninh Bình',
                'lat' => 20.648500,
                'lng' => 105.909500,
                'description' => 'Trường Trung học cơ sở Duy Hà có đội ngũ giáo viên giàu kinh nghiệm, đạt nhiều thành tích xuất sắc trong công tác bồi dưỡng học sinh giỏi và giáo dục toàn diện.',
                'image' => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=600&auto=format&fit=crop',
            ],
            [
                'name' => 'Trạm Y tế Phường Duy Hà',
                'category' => 'health',
                'address' => 'Đường Trần Hưng Đạo, Phường Duy Hà, Tỉnh Ninh Bình',
                'lat' => 20.646500,
                'lng' => 105.908500,
                'description' => 'Cơ sở y tế ban đầu của phường Duy Hà chịu trách nhiệm khám chữa bệnh cơ bản, tiêm chủng mở rộng, chăm sóc sức khỏe bà mẹ trẻ em và phòng chống dịch bệnh tại cộng đồng.',
                'image' => 'https://images.unsplash.com/photo-1586773860418-d37222d8fce2?q=80&w=600&auto=format&fit=crop',
            ],
        ];

        // 3. Lấy thông tin Phường Duy Hà (mã 13336)
        $duyHaUnit = AdministrativeUnit::where('code', '13336')->first();

        foreach ($places as $p) {
            Place::updateOrCreate(
                ['name' => $p['name']],
                [
                    'category' => $p['category'],
                    'address' => $p['address'],
                    'lat' => $p['lat'],
                    'lng' => $p['lng'],
                    'description' => $p['description'],
                    'image' => $p['image'],
                    'administrative_unit_id' => $duyHaUnit ? $duyHaUnit->id : null,
                    'status' => 'active',
                ]
            );
        }

        // 4. Tạo dữ liệu mẫu cho neighborhoods (Tổ dân phố trước và sau sáp nhập)
        $neighborhoods = [
            // Tổ dân phố cũ (trước sáp nhập) - type: old
            ['name' => 'TDP Chuồng', 'type' => 'old', 'group_code' => 'chuong', 'households' => 712, 'people' => 2348, 'area_ha' => 34.20],
            ['name' => 'TDP Động Linh', 'type' => 'old', 'group_code' => 'dong-linh-trang', 'households' => 318, 'people' => 1096, 'area_ha' => 62.90],
            ['name' => 'TDP Trịnh', 'type' => 'old', 'group_code' => 'dong-linh-trang', 'households' => 298, 'people' => 1038, 'area_ha' => 21.60],
            ['name' => 'TDP Ninh Lão', 'type' => 'old', 'group_code' => 'duy-minh', 'households' => 357, 'people' => 1249, 'area_ha' => 42.60],
            ['name' => 'TDP Trung', 'type' => 'old', 'group_code' => 'duy-minh', 'households' => 203, 'people' => 678, 'area_ha' => 20.40],
            ['name' => 'TDP Tú', 'type' => 'old', 'group_code' => 'ngoc-tu', 'households' => 327, 'people' => 1158, 'area_ha' => 130.50],
            ['name' => 'TDP Ngọc Thị', 'type' => 'old', 'group_code' => 'ngoc-tu', 'households' => 403, 'people' => 1355, 'area_ha' => 288.10],
            ['name' => 'TDP Đông Hải', 'type' => 'old', 'group_code' => 'dong-hai', 'households' => 634, 'people' => 2168, 'area_ha' => 164.60],
            ['name' => 'TDP Hương Cát', 'type' => 'old', 'group_code' => 'huong-cat', 'households' => 561, 'people' => 2046, 'area_ha' => 187.80],
            ['name' => 'TDP Tứ Giáp', 'type' => 'old', 'group_code' => 'duy-hai', 'households' => 346, 'people' => 1243, 'area_ha' => 82.50],
            ['name' => 'TDP Tam Giáp', 'type' => 'old', 'group_code' => 'duy-hai', 'households' => 379, 'people' => 1284, 'area_ha' => 84.60],
            ['name' => 'TDP An Nhân', 'type' => 'old', 'group_code' => 'hoang-dong', 'households' => 201, 'people' => 669, 'area_ha' => 20.30],
            ['name' => 'TDP Hoàng Thượng', 'type' => 'old', 'group_code' => 'hoang-dong', 'households' => 326, 'people' => 1174, 'area_ha' => 46.80],
            ['name' => 'TDP Hoàng Hạ', 'type' => 'old', 'group_code' => 'hoang-dong', 'households' => 243, 'people' => 899, 'area_ha' => 72.50],
            ['name' => 'TDP Bạch Xá', 'type' => 'old', 'group_code' => 'bach-xa', 'households' => 663, 'people' => 2404, 'area_ha' => 131.40],
            ['name' => 'TDP Ngọc Động', 'type' => 'old', 'group_code' => 'ngoc-dong', 'households' => 796, 'people' => 2806, 'area_ha' => 155.50],

            // Tổ dân phố mới (sau sáp nhập) - type: new
            ['name' => 'TDP Chuồng', 'type' => 'new', 'group_code' => 'chuong', 'leader_name' => 'Đại úy Nguyễn Văn Việt', 'leader_phone' => '0972.280.538', 'households' => 712, 'people' => 2348, 'area_ha' => 65.00],
            ['name' => 'TDP Đông Linh Trang', 'type' => 'new', 'group_code' => 'dong-linh-trang', 'leader_name' => 'Thiếu úy Vũ Văn Hào', 'leader_phone' => '0796.191.310', 'households' => 616, 'people' => 2134, 'area_ha' => 84.50],
            ['name' => 'TDP Duy Minh', 'type' => 'new', 'group_code' => 'duy-minh', 'leader_name' => 'Đại úy Trần Hữu Tiến', 'leader_phone' => '0986.361.395', 'households' => 560, 'people' => 1927, 'area_ha' => 63.00],
            ['name' => 'TDP Ngọc Tú', 'type' => 'new', 'group_code' => 'ngoc-tu', 'leader_name' => 'Thiếu tá Nguyễn Minh Tiến', 'leader_phone' => '0359.290.686', 'households' => 730, 'people' => 2513, 'area_ha' => 418.60],
            ['name' => 'TDP Đông Hải', 'type' => 'new', 'group_code' => 'dong-hai', 'leader_name' => 'Thiếu tá Nguyễn Văn Tuân', 'leader_phone' => '0866.697.088', 'households' => 634, 'people' => 2168, 'area_ha' => 135.80],
            ['name' => 'TDP Hương Cát', 'type' => 'new', 'group_code' => 'huong-cat', 'leader_name' => 'Thượng úy Đinh Xuân Trường', 'leader_phone' => '0585.288.686', 'households' => 561, 'people' => 2046, 'area_ha' => 187.80],
            ['name' => 'TDP Duy Hải', 'type' => 'new', 'group_code' => 'duy-hai', 'leader_name' => 'Thượng úy Đinh Xuân Trường', 'leader_phone' => '0585.288.686', 'households' => 725, 'people' => 2527, 'area_ha' => 165.10],
            ['name' => 'TDP Hoàng Đồng', 'type' => 'new', 'group_code' => 'hoang-dong', 'leader_name' => 'Thiếu tá Ngô Vinh Quang', 'leader_phone' => '0977.597.118', 'households' => 770, 'people' => 2742, 'area_ha' => 139.60],
            ['name' => 'TDP Bạch Xá', 'type' => 'new', 'group_code' => 'bach-xa', 'leader_name' => 'Đại úy Đoàn Văn Chương', 'leader_phone' => '0911.940.111', 'households' => 663, 'people' => 2404, 'area_ha' => 131.40],
            ['name' => 'TDP Ngọc Động', 'type' => 'new', 'group_code' => 'ngoc-dong', 'leader_name' => 'Đại úy Vũ Ngọc Quang', 'leader_phone' => '0978.530.570', 'households' => 796, 'people' => 2806, 'area_ha' => 155.50],
        ];

        foreach ($neighborhoods as $n) {
            \App\Models\Neighborhood::updateOrCreate(
                [
                    'name' => $n['name'],
                    'type' => $n['type'],
                ],
                [
                    'group_code' => $n['group_code'],
                    'leader_name' => $n['leader_name'] ?? null,
                    'leader_phone' => $n['leader_phone'] ?? null,
                    'households' => $n['households'],
                    'people' => $n['people'],
                    'area_ha' => $n['area_ha'],
                    'status' => 'active',
                ]
            );
        }

        // 5. Tạo dữ liệu mẫu cho các Sự kiện kỷ niệm (CelebrationEvent)
        $events = [
            [
                'name' => 'Kỷ niệm Ngày Giải phóng miền Nam, Thống nhất đất nước (30/04) & Quốc tế Lao động (01/05)',
                'month' => 4,
                'day' => 30,
                'description' => 'Tuyên dương tinh thần đoàn kết toàn dân và tri ân thế hệ cha anh đã có công lao to lớn đưa đất nước đi đến ngày thống nhất, hòa bình, đồng thời tôn vinh giai cấp công nhân lao động.',
            ],
            [
                'name' => 'Kỷ niệm ngày Thương binh - Liệt sĩ (27/07)',
                'month' => 7,
                'day' => 27,
                'is_featured' => true,
                'description' => 'Hoạt động tri ân sâu sắc, đời đời nhớ ơn các anh hùng liệt sĩ, các thương binh, bệnh binh và những gia đình chính sách đã hy sinh xương máu cho nền độc lập tự do của Tổ quốc.',
            ],
            [
                'name' => 'Kỷ niệm Ngày Quốc khánh nước Cộng hòa Xã hội Chủ nghĩa Việt Nam (02/09)',
                'month' => 9,
                'day' => 2,
                'is_featured' => false,
                'description' => 'Tri ân các gia đình có công với cách mạng, lão thành cách mạng và nhân dân đã có nhiều cống hiến cho sự nghiệp khai sinh ra nước Việt Nam Dân chủ Cộng hòa.',
            ],
            [
                'name' => 'Kỷ niệm Ngày thành lập Quân đội Nhân dân Việt Nam (22/12)',
                'month' => 12,
                'day' => 22,
                'is_featured' => false,
                'description' => 'Tôn vinh các gia đình cựu chiến binh, quân nhân xuất ngũ có hoàn cảnh khó khăn và những cá nhân đã có nhiều cống hiến vẻ vang trong lực lượng vũ trang nhân dân Việt Nam.',
            ],
        ];

        $eventModels = [];
        foreach ($events as $ev) {
            $eventModels[$ev['month'] . '-' . $ev['day']] = \App\Models\CelebrationEvent::updateOrCreate(
                ['month' => $ev['month'], 'day' => $ev['day']],
                [
                    'name' => $ev['name'],
                    'description' => $ev['description'],
                    'is_featured' => $ev['is_featured'] ?? false,
                    'status' => 'active',
                ]
            );
        }

        // 6. Tạo dữ liệu mẫu cho các Gia đình có công (MeritoriousFamily)
        // Lấy một số TDP mới làm tham chiếu liên kết
        $tdpHoangDong = \App\Models\Neighborhood::where('name', 'Hoàng Đồng')->where('type', 'new')->first();
        $tdpNgocTu = \App\Models\Neighborhood::where('name', 'Ngọc Tú')->where('type', 'new')->first();
        $tdpDuyHai = \App\Models\Neighborhood::where('name', 'Duy Hải')->where('type', 'new')->first();
        $tdpDongLinhTrang = \App\Models\Neighborhood::where('name', 'Đông Linh Trang')->where('type', 'new')->first();
        $tdpDuyMinh = \App\Models\Neighborhood::where('name', 'Duy Minh')->where('type', 'new')->first();

        $families = [
            // Sự kiện 27/07 - Thương binh Liệt sĩ
            [
                'name' => 'Gia đình Liệt sĩ Nguyễn Văn Đạt',
                'type' => 'Gia đình Liệt sĩ',
                'neighborhood_id' => $tdpHoangDong ? $tdpHoangDong->id : null,
                'address' => 'Số 12, ngõ Hoàng Thượng, phường Duy Hà',
                'representative_name' => 'Nguyễn Thị Thu (Vợ liệt sĩ)',
                'phone' => '0912345601',
                'benefit_details' => 'Nhận 2.500.000đ tiền mặt, sổ tiết kiệm 5.000.000đ và giỏ quà trị giá 500.000đ từ UBND phường.',
                'event_key' => '7-27',
            ],
            [
                'name' => 'Gia đình Thương binh Phạm Minh Hoàng',
                'type' => 'Thương binh 1/4',
                'neighborhood_id' => $tdpDuyHai ? $tdpDuyHai->id : null,
                'address' => 'Số 85, đường Trần Hưng Đạo, phường Duy Hà',
                'representative_name' => 'Phạm Minh Hoàng (Thương binh)',
                'phone' => '0903456781',
                'benefit_details' => 'Nhận 2.000.000đ hỗ trợ thương tật và thẻ bảo hiểm y tế miễn phí kèm giỏ quà chăm sóc sức khỏe.',
                'event_key' => '7-27',
            ],
            [
                'name' => 'Gia đình Bệnh binh Lê Văn Tường',
                'type' => 'Bệnh binh 2/4',
                'neighborhood_id' => $tdpNgocTu ? $tdpNgocTu->id : null,
                'address' => 'Số 42, ngõ Ngọc Thị, phường Duy Hà',
                'representative_name' => 'Lê Văn Tường (Bệnh binh)',
                'phone' => '0982345678',
                'benefit_details' => 'Nhận 1.500.000đ trợ cấp khó khăn và quà tặng ngày 27/7.',
                'event_key' => '7-27',
            ],

            // Sự kiện 02/09 - Quốc khánh
            [
                'name' => 'Gia đình Lão thành cách mạng Nguyễn Hữu Thọ',
                'type' => 'Lão thành Cách mạng',
                'neighborhood_id' => $tdpDuyMinh ? $tdpDuyMinh->id : null,
                'address' => 'TDP Duy Minh, phường Duy Hà',
                'representative_name' => 'Nguyễn Hữu Thọ',
                'phone' => '0975123401',
                'benefit_details' => 'Tặng Huy chương chiến sĩ vẻ vang và hỗ trợ sửa chữa nhà tình nghĩa trị giá 40.000.000đ.',
                'event_key' => '9-2',
            ],
            [
                'name' => 'Gia đình có công Cách mạng Trần Thị Xuân',
                'type' => 'Gia đình có công với Cách mạng',
                'neighborhood_id' => $tdpHoangDong ? $tdpHoangDong->id : null,
                'address' => 'Số 19, đường Lê Thái Tổ, phường Duy Hà',
                'representative_name' => 'Trần Thị Xuân',
                'phone' => '0915123499',
                'benefit_details' => 'Hỗ trợ 1.500.000đ và quà mừng ngày Quốc khánh 2/9.',
                'event_key' => '9-2',
            ],

            // Sự kiện 30/04 - Giải phóng miền Nam
            [
                'name' => 'Gia đình Cựu chiến binh kháng chiến chống Mỹ Trần Văn Giang',
                'type' => 'Cựu chiến binh',
                'neighborhood_id' => $tdpDongLinhTrang ? $tdpDongLinhTrang->id : null,
                'address' => 'TDP Đông Linh Trang, phường Duy Hà',
                'representative_name' => 'Trần Văn Giang',
                'phone' => '0914123402',
                'benefit_details' => 'Tặng kỷ niệm chương kháng chiến hạng Nhì và hỗ trợ 1.000.000đ từ quỹ đền ơn đáp nghĩa.',
                'event_key' => '4-30',
            ],

            // Sự kiện 22/12 - Thành lập Quân đội
            [
                'name' => 'Gia đình Liệt sĩ chống Mỹ Nguyễn Hữu Tài',
                'type' => 'Gia đình Liệt sĩ',
                'neighborhood_id' => $tdpDuyMinh ? $tdpDuyMinh->id : null,
                'address' => 'TDP Duy Minh, phường Duy Hà',
                'representative_name' => 'Nguyễn Hữu Đức (Em trai liệt sĩ)',
                'phone' => '0975123403',
                'benefit_details' => 'Hỗ trợ thờ cúng liệt sĩ 1.000.000đ và giỏ quà tri ân ngày 22/12.',
                'event_key' => '12-22',
            ],
        ];

        foreach ($families as $f) {
            $event = $eventModels[$f['event_key']] ?? null;
            if ($event) {
                \App\Models\MeritoriousFamily::updateOrCreate(
                    [
                        'name' => $f['name'],
                        'celebration_event_id' => $event->id,
                    ],
                    [
                        'type' => $f['type'],
                        'neighborhood_id' => $f['neighborhood_id'],
                        'address' => $f['address'],
                        'representative_name' => $f['representative_name'],
                        'phone' => $f['phone'],
                        'benefit_details' => $f['benefit_details'],
                        'status' => 'active',
                    ]
                );
            }
        }

        // 7. Tạo dữ liệu thực tế cho Đơn vị & Khối công tác (departments)
        \App\Models\Department::truncate();
        $departments = [
            ['code' => 'cskv', 'name' => 'Cảnh sát khu vực (CSKV)', 'color' => 'danger', 'sort_order' => 1, 'description' => 'Khối Cảnh sát khu vực quản lý an ninh các Tổ dân phố'],
            ['code' => 'cong_an', 'name' => 'Công an Phường Duy Hà', 'color' => 'warning', 'sort_order' => 2, 'description' => 'Ban chỉ huy và lực lượng Công an Phường Duy Hà'],
            ['code' => 'dang_uy', 'name' => 'Đảng ủy Phường', 'color' => 'primary', 'sort_order' => 3, 'description' => 'Khối Đảng ủy và cơ quan Đảng Phường Duy Hà'],
            ['code' => 'chinh_quyen', 'name' => 'UBND / Chính quyền', 'color' => 'success', 'sort_order' => 4, 'description' => 'UBND và các phòng ban quản lý Nhà nước'],
            ['code' => 'ttpvhcc', 'name' => 'Hành chính công', 'color' => 'info', 'sort_order' => 5, 'description' => 'Trung tâm Phục vụ Hành chính công Phường Duy Hà'],
        ];
        foreach ($departments as $dept) {
            \App\Models\Department::create($dept);
        }

        // 8. Tạo dữ liệu thực tế cho bảng Lãnh đạo Phường (officials)
        \App\Models\Official::truncate();

        $officials = [
            // ĐẢNG ỦY
            ['name' => 'Ngô Thị Lan Hương', 'role' => 'Bí thư Đảng ủy, chủ tịch HĐND', 'phone' => '0983542466', 'neighborhood_name' => 'Đảng ủy Phường Duy Hà', 'department' => 'dang_uy', 'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200&auto=format&fit=crop', 'avatar_color' => '#DC2626'],
            ['name' => 'Trần Hạng Vũ', 'role' => 'Phó Bí thư thường trực', 'phone' => '0986583169', 'neighborhood_name' => 'Đảng ủy Phường Duy Hà', 'department' => 'dang_uy', 'avatar' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=200&auto=format&fit=crop', 'avatar_color' => '#1D4ED8'],
            ['name' => 'Đàm Thanh Minh', 'role' => 'Chánh văn phòng Đảng ủy', 'phone' => '0916185222', 'neighborhood_name' => 'Đảng ủy Phường Duy Hà', 'department' => 'dang_uy', 'avatar' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=200&auto=format&fit=crop', 'avatar_color' => '#2563EB'],

            // CHÍNH QUYỀN
            ['name' => 'Nguyễn Như Uy', 'role' => 'Phó Bí thư Đảng ủy, Chủ tịch UBND', 'phone' => '0912220182', 'neighborhood_name' => 'UBND Phường Duy Hà', 'department' => 'chinh_quyen', 'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=200&auto=format&fit=crop', 'avatar_color' => '#059669'],
            ['name' => 'Nguyễn Duy Khiêm', 'role' => 'Chánh VP HĐND-UBND', 'phone' => '0942893028', 'neighborhood_name' => 'UBND Phường Duy Hà', 'department' => 'chinh_quyen', 'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop', 'avatar_color' => '#0284C7'],
            ['name' => 'Kiều Ngọc Kiên', 'role' => 'Trưởng phòng Văn hoá - Xã hội', 'phone' => '0973268310', 'neighborhood_name' => 'UBND Phường Duy Hà', 'department' => 'chinh_quyen', 'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=200&auto=format&fit=crop', 'avatar_color' => '#7C3AED'],
            ['name' => 'Nguyễn Đức Thuận', 'role' => 'Trưởng phòng Kinh tế hạ tầng', 'phone' => '0912164372', 'neighborhood_name' => 'UBND Phường Duy Hà', 'department' => 'chinh_quyen', 'avatar' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?q=80&w=200&auto=format&fit=crop', 'avatar_color' => '#D97706'],

            // TTPVHCC
            ['name' => 'Nguyễn Tiến Đạt', 'role' => 'Giám đốc TTPVHCC', 'phone' => '0915802179', 'neighborhood_name' => 'Trung tâm Phục vụ Hành chính công', 'department' => 'ttpvhcc', 'avatar' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=200&auto=format&fit=crop', 'avatar_color' => '#10B981'],

            // CÔNG AN & CẢNH SÁT KHU VỰC (CSKV)
            ['name' => 'Thiếu tá Đỗ Đình Thái', 'role' => 'Phó Trưởng Công an Phường', 'phone' => '0945.222.269', 'neighborhood_name' => 'Công an Phường Duy Hà', 'department' => 'cong_an', 'avatar' => null, 'avatar_color' => '#DC2626'],
            ['name' => 'Đại úy Trần Hữu Tiến', 'role' => 'Cán bộ CSKV Phụ trách TDP Duy Minh', 'phone' => '0986.361.395', 'neighborhood_name' => 'TDP Duy Minh', 'department' => 'cskv', 'avatar' => null, 'avatar_color' => '#B91C1C'],
            ['name' => 'Đại úy Đoàn Văn Chương', 'role' => 'Cán bộ CSKV Phụ trách TDP Bạch Xá', 'phone' => '0911.940.111', 'neighborhood_name' => 'TDP Bạch Xá', 'department' => 'cskv', 'avatar' => null, 'avatar_color' => '#B91C1C'],
            ['name' => 'Thiếu úy Vũ Văn Hào', 'role' => 'Cán bộ CSKV Phụ trách TDP Động Linh Trang', 'phone' => '0796.191.310', 'neighborhood_name' => 'TDP Động Linh Trang', 'department' => 'cskv', 'avatar' => null, 'avatar_color' => '#B91C1C'],
            ['name' => 'Thiếu tá Nguyễn Minh Tiến', 'role' => 'Cán bộ CSKV Phụ trách TDP Ngọc Tú', 'phone' => '0359.290.686', 'neighborhood_name' => 'TDP Ngọc Tú', 'department' => 'cskv', 'avatar' => null, 'avatar_color' => '#B91C1C'],
            ['name' => 'Đại úy Nguyễn Văn Việt', 'role' => 'Cán bộ CSKV Phụ trách TDP Chuồng', 'phone' => '0972.280.538', 'neighborhood_name' => 'TDP Chuồng', 'department' => 'cskv', 'avatar' => null, 'avatar_color' => '#B91C1C'],
            ['name' => 'Thượng úy Đinh Xuân Trường', 'role' => 'Cán bộ CSKV Phụ trách TDP Hương Cát, TDP Duy Hải', 'phone' => '0585.288.686', 'neighborhood_name' => 'TDP Hương Cát, TDP Duy Hải', 'department' => 'cskv', 'avatar' => null, 'avatar_color' => '#B91C1C'],
            ['name' => 'Thiếu tá Ngô Vinh Quang', 'role' => 'Cán bộ CSKV Phụ trách TDP Hoàng Đồng', 'phone' => '0977.597.118', 'neighborhood_name' => 'TDP Hoàng Đồng', 'department' => 'cskv', 'avatar' => null, 'avatar_color' => '#B91C1C'],
            ['name' => 'Thiếu tá Nguyễn Văn Tuân', 'role' => 'Cán bộ CSKV Phụ trách TDP Đông Hải', 'phone' => '0866.697.088', 'neighborhood_name' => 'TDP Đông Hải', 'department' => 'cskv', 'avatar' => null, 'avatar_color' => '#B91C1C'],
            ['name' => 'Đại úy Vũ Ngọc Quang', 'role' => 'Cán bộ CSKV Phụ trách TDP Ngọc Động', 'phone' => '0978.530.570', 'neighborhood_name' => 'TDP Ngọc Động', 'department' => 'cskv', 'avatar' => null, 'avatar_color' => '#B91C1C'],
        ];

        foreach ($officials as $off) {
            \App\Models\Official::create([
                'name' => $off['name'],
                'role' => $off['role'],
                'phone' => $off['phone'],
                'neighborhood_name' => $off['neighborhood_name'],
                'department' => $off['department'],
                'avatar' => $off['avatar'],
                'avatar_color' => $off['avatar_color'],
                'status' => 'active',
            ]);
        }
    }
}

