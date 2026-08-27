<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Setting;
use Filament\Notifications\Notification;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ManageSubpageBanners extends Page
{
    use WithFileUploads;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Banner các Trang con';

    protected static ?string $title = 'Quản lý Banner Tiêu đề các Menu con (Subpage Banners)';

    public static function getNavigationGroup(): ?string
    {
        return 'Cài đặt';
    }

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.manage-subpage-banners';

    public $banners = [];

    // Edit modal state
    public $isModalOpen = false;
    public $editingKey = null;
    public $editPageName = '';
    public $editPageUrl = '';
    public $editBadgeText = '';
    public $editBadgeIcon = '';
    public $editTitle = '';
    public $editSubtitle = '';
    public $editBgImage = '';
    public $bgUpload = null;

    // Citizen Reception Rules State
    public $receptionRules = [];

    // Waste Classification Guide State (Lịch thu gom rác)
    public $wasteGuideTitle = '';
    public $wasteGuideSubtitle = '';
    public $wasteCategories = [];
    public $wasteCategoryUploads = [];
    public $wasteRegulation = '';

    // Feedback Process Guide State (Quy trình phản ánh kiến nghị)
    public $feedbackProcessTitle = '';
    public $feedbackProcessSteps = [];

    public function mount()
    {
        $this->loadBanners();
    }

    public function defaultBanners(): array
    {
        return [
            // Nhóm 1: Dịch vụ công & Thủ tục
            'procedures' => [
                'page_code' => 'procedures',
                'page_name' => 'Thủ tục hành chính',
                'group_name' => 'Dịch vụ công & Thủ tục',
                'page_url' => '/procedures.html?tab=procedures',
                'badge_text' => 'UBND PHƯỜNG DUY HÀ — CỔNG HƯỚNG DẪN DỊCH VỤ CÔNG',
                'badge_icon' => 'verified',
                'title' => 'Hướng dẫn Thủ tục Hành chính',
                'subtitle' => 'Tra cứu chi tiết quy trình thực hiện, hồ sơ giấy tờ cần chuẩn bị và tải mẫu biểu trực tiếp phục vụ công dân Phường Duy Hà.',
                'bg_image' => '/hero-bg.jpg',
            ],
            'policies' => [
                'page_code' => 'policies',
                'page_name' => 'Chính sách & Quy định',
                'group_name' => 'Dịch vụ công & Thủ tục',
                'page_url' => '/procedures.html?tab=policies',
                'badge_text' => 'UBND PHƯỜNG DUY HÀ — HỆ THỐNG VĂN BẢN & CHÍNH SÁCH HÀNH CHÍNH',
                'badge_icon' => 'policy',
                'title' => 'Chính sách & Quy định Hành chính',
                'subtitle' => 'Tra cứu đầy đủ các văn bản quy phạm pháp luật, Nghị định, Thông tư và Quyết định an sinh xã hội cho công dân Phường Duy Hà.',
                'bg_image' => '/hero-bg.jpg',
            ],
            'video_guides' => [
                'page_code' => 'video_guides',
                'page_name' => 'Video hướng dẫn thủ tục',
                'group_name' => 'Dịch vụ công & Thủ tục',
                'page_url' => '/procedures.html?tab=videos',
                'badge_text' => 'UBND PHƯỜNG DUY HÀ — KHO VIDEO HƯỚNG DẪN DỊCH VỤ CÔNG',
                'badge_icon' => 'play_circle',
                'title' => 'Kho Video Hướng dẫn Thủ tục Hành chính',
                'subtitle' => 'Xem chi tiết các đoạn phim hướng dẫn thao tác trực quan từng bước nộp hồ sơ Dịch vụ công Quốc gia, Đăng ký tạm trú và VNeID.',
                'bg_image' => '/hero-bg.jpg',
            ],

            // Nhóm 2: Lịch công tác & Tiếp dân
            'citizen_reception' => [
                'page_code' => 'citizen_reception',
                'page_name' => 'Lịch tiếp công dân định kỳ',
                'group_name' => 'Lịch công tác & Tiếp dân',
                'page_url' => '/citizen-reception.html',
                'badge_text' => 'Công khai — Minh bạch — Kịp thời',
                'badge_icon' => 'calendar_month',
                'title' => 'Lịch tiếp công dân định kỳ',
                'subtitle' => 'Thông tin lịch trực tiếp công dân của Lãnh đạo Đảng ủy, HĐND, UBND và Ban chỉ huy Công an phường.',
                'bg_image' => '/hero-bg.jpg',
            ],
            'waste_schedule' => [
                'page_code' => 'waste_schedule',
                'page_name' => 'Lịch thu gom rác sinh hoạt',
                'group_name' => 'Lịch công tác & Tiếp dân',
                'page_url' => '/waste-schedule.html',
                'badge_text' => 'Vệ sinh môi trường đô thị',
                'badge_icon' => 'delete',
                'title' => 'Lịch thu gom rác sinh hoạt',
                'subtitle' => 'Tra cứu khung giờ xe gom rác, ngày thu gom định kỳ và các điểm tập kết rác thải theo từng Tổ dân phố.',
                'bg_image' => '/images/tdp_merger_banner_bg.jpg',
            ],

            // Nhóm 3: Tổ chức & Chính quyền
            'agencies' => [
                'page_code' => 'agencies',
                'page_name' => 'Danh sách Cơ quan hành chính',
                'group_name' => 'Tổ chức & Chính quyền',
                'page_url' => '/agencies.html',
                'badge_text' => 'Trụ sở làm việc & Cơ sở công lập',
                'badge_icon' => 'apartment',
                'title' => 'Danh sách Cơ quan hành chính',
                'subtitle' => 'Địa chỉ, số điện thoại liên hệ, bản đồ vị trí và hình ảnh các cơ quan Đảng, chính quyền, công an, trường học, trạm y tế trên địa bàn Phường Duy Hà.',
                'bg_image' => '/hero-bg.jpg',
            ],
            'officials' => [
                'page_code' => 'officials',
                'page_name' => 'Danh sách Cán bộ Phường',
                'group_name' => 'Tổ chức & Chính quyền',
                'page_url' => '/officials.html',
                'badge_text' => 'Hệ thống danh bạ liên lạc chính thức',
                'badge_icon' => 'contacts',
                'title' => 'Danh sách Cán bộ Phường',
                'subtitle' => 'Tra cứu nhanh số điện thoại Bí thư Đảng ủy, Chủ tịch UBND, Công an phụ trách địa bàn và các cán bộ công chức Phường Duy Hà.',
                'bg_image' => '/images/tdp_merger_banner_bg.jpg',
            ],
            'tdp_merger' => [
                'page_code' => 'tdp_merger',
                'page_name' => 'Tổ dân phố & Cán bộ TDP',
                'group_name' => 'Tổ chức & Chính quyền',
                'page_url' => '/tdp-merger.html',
                'badge_text' => 'Thông tin sáp nhập Đơn vị Hành chính 2026',
                'badge_icon' => 'call_merge',
                'title' => 'Tổ dân phố & Cán bộ TDP',
                'subtitle' => 'Bảng số liệu chính thức so sánh hiện trạng 16 Tổ dân phố cũ và 10 Tổ dân phố mới dự kiến sau sáp nhập. Tra cứu thông tin hộ dân, nhân khẩu, diện tích và danh sách cán bộ phụ trách.',
                'bg_image' => '/images/tdp_merger_banner_bg.jpg',
            ],

            // Nhóm 4: An sinh xã hội & Tra cứu
            'meritorious_families' => [
                'page_code' => 'meritorious_families',
                'page_name' => 'Gia đình chính sách & Người có công',
                'group_name' => 'An sinh xã hội & Tra cứu',
                'page_url' => '/meritorious-families.html',
                'badge_text' => 'Đền ơn đáp nghĩa — Uống nước nhớ nguồn',
                'badge_icon' => 'military_tech',
                'title' => 'Gia đình chính sách & Người có công',
                'subtitle' => 'Thông tin tri ân các Mẹ Việt Nam Anh hùng, Anh hùng LLVT, Thương bệnh binh và Gia đình Liệt sĩ trên địa bàn Phường Duy Hà.',
                'bg_image' => '/images/tdp_merger_banner_bg.jpg',
            ],
            'feedback' => [
                'page_code' => 'feedback',
                'page_name' => 'Gửi Phản ánh & Kiến nghị',
                'group_name' => 'An sinh xã hội & Tra cứu',
                'page_url' => '/feedback.html',
                'badge_text' => 'Tiếp nhận ý kiến Nhân dân',
                'badge_icon' => 'rate_review',
                'title' => 'Gửi Phản ánh & Kiến nghị trực tuyến',
                'subtitle' => 'Gửi phản ánh, đóng góp ý kiến về an ninh trật tự, vệ sinh môi trường, văn minh đô thị và thủ tục hành chính.',
                'bg_image' => '/hero-bg.jpg',
            ],
        ];
    }

    public function loadBanners()
    {
        $setting = Setting::where('key', 'subpage_banners')->first();
        $stored = [];
        if ($setting && !empty($setting->value)) {
            $decoded = json_decode($setting->value, true);
            if (is_array($decoded)) {
                $stored = $decoded;
            }
        }

        $defaults = $this->defaultBanners();
        $merged = [];

        foreach ($defaults as $key => $defaultItem) {
            if (isset($stored[$key]) && is_array($stored[$key])) {
                $merged[$key] = array_merge($defaultItem, $stored[$key]);
                // Ensure page_name, group_name & page_url are synced with the sub-menu names
                $merged[$key]['page_name'] = $defaultItem['page_name'];
                $merged[$key]['group_name'] = $defaultItem['group_name'];
                $merged[$key]['page_url'] = $defaultItem['page_url'];
            } else {
                $merged[$key] = $defaultItem;
            }
        }

        $this->banners = $merged;
    }

    public function openEditModal(string $key)
    {
        if (!isset($this->banners[$key])) {
            return;
        }

        $item = $this->banners[$key];
        $this->editingKey = $key;
        $this->editPageName = $item['page_name'] ?? '';
        $this->editPageUrl = $item['page_url'] ?? '';
        $this->editBadgeText = $item['badge_text'] ?? '';
        $this->editBadgeIcon = $item['badge_icon'] ?? 'info';
        $this->editTitle = $item['title'] ?? '';
        $this->editSubtitle = $item['subtitle'] ?? '';
        $this->editBgImage = $item['bg_image'] ?? '/hero-bg.jpg';
        $this->bgUpload = null;
        $this->isModalOpen = true;

        // Load Citizen Reception Rules if editing citizen_reception
        if ($key === 'citizen_reception') {
            $rulesRaw = Setting::where('key', 'citizen_reception_rules')->value('value');
            if ($rulesRaw) {
                $decoded = json_decode($rulesRaw, true);
                $this->receptionRules = is_array($decoded) ? $decoded : [];
            } else {
                $this->receptionRules = [
                    'Xuất trình giấy tờ tùy thân (Căn cước công dân hoặc VNeID Mức 2) khi vào phòng tiếp dân.',
                    'Trình bày nội dung rõ ràng, trung thực và cung cấp chứng cứ, tài liệu liên quan đến vụ việc.',
                    'Giữ gìn trật tự, trang phục lịch sự, chấp hành hướng dẫn của cán bộ tiếp dân.',
                    'Nghiêm cấm mang theo vũ khí, chất cháy nổ hoặc các vật dụng gây nguy hiểm vào trụ sở.',
                ];
            }
        }

        // Load Waste Classification Guide if editing waste_schedule
        if ($key === 'waste_schedule') {
            $guideRaw = Setting::where('key', 'waste_classification_guide')->value('value');
            if ($guideRaw) {
                $decoded = json_decode($guideRaw, true);
                $this->wasteGuideTitle = $decoded['title'] ?? 'Hướng dẫn phân loại rác tại nguồn';
                $this->wasteGuideSubtitle = $decoded['subtitle'] ?? 'Thực hiện Luật Bảo vệ môi trường — Chung tay xây dựng Phường Duy Hà Xanh - Sạch - Văn minh';
                $this->wasteCategories = $decoded['categories'] ?? [];
                $this->wasteRegulation = $decoded['regulation'] ?? 'Bỏ rác đúng giờ trước khi xe đến 15-30 phút. Hành vi vứt rác bừa bãi ra vỉa hè, lòng đường bị phạt tiền từ 500.000đ — 2.000.000đ theo Nghị định 45/2022/NĐ-CP.';
            } else {
                $this->wasteGuideTitle = 'Hướng dẫn phân loại rác tại nguồn';
                $this->wasteGuideSubtitle = 'Thực hiện Luật Bảo vệ môi trường — Chung tay xây dựng Phường Duy Hà Xanh - Sạch - Văn minh';
                $this->wasteCategories = [
                    [
                        'title' => '1. RÁC HỮU CƠ (DỄ PHÂN HỦY)',
                        'desc' => 'Thức ăn thừa, rau củ quả thải loại, vỏ trái cây, bã chè, lá cây nhỏ...',
                        'icon' => 'compost',
                        'theme' => 'emerald',
                    ],
                    [
                        'title' => '2. RÁC TÁI CHẾ (PHẾ LIỆU)',
                        'desc' => 'Bìa carton, giấy báo cũ, chai lọ nhựa, vỏ lon kim loại, đồ nhựa gia dụng...',
                        'icon' => 'inventory_2',
                        'theme' => 'amber',
                    ],
                    [
                        'title' => '3. RÁC THẢI CÒN LẠI (VÔ CƠ)',
                        'desc' => 'Túi nilon bẩn, hộp xốp, tã bỉm, gốm sứ vỡ, rác quét nhà, cành cây tỉa...',
                        'icon' => 'delete_sweep',
                        'theme' => 'slate',
                    ],
                ];
                $this->wasteRegulation = 'Bỏ rác đúng giờ trước khi xe đến 15-30 phút. Hành vi vứt rác bừa bãi ra vỉa hè, lòng đường bị phạt tiền từ 500.000đ — 2.000.000đ theo Nghị định 45/2022/NĐ-CP.';
            }
        }

        // Load Feedback Process Guide if editing feedback
        if ($key === 'feedback') {
            $feedbackGuideRaw = Setting::where('key', 'feedback_process_guide')->value('value');
            if ($feedbackGuideRaw) {
                $decoded = json_decode($feedbackGuideRaw, true);
                $this->feedbackProcessTitle = $decoded['title'] ?? 'QUY TRÌNH 4 BƯỚC TIẾP NHẬN';
                $this->feedbackProcessSteps = $decoded['steps'] ?? [];
            } else {
                $this->feedbackProcessTitle = 'QUY TRÌNH 4 BƯỚC TIẾP NHẬN';
                $this->feedbackProcessSteps = [
                    [
                        'title' => 'Bước 1: Tiếp nhận phản ánh',
                        'desc' => 'Người dân gửi thông tin phản ánh qua hệ thống.',
                    ],
                    [
                        'title' => 'Bước 2: Phân loại và chuyển xử lý',
                        'desc' => 'Phản ánh được phân loại và chuyển đến bộ phận phụ trách.',
                    ],
                    [
                        'title' => 'Bước 3: Kiểm tra, xác minh',
                        'desc' => 'Cán bộ phụ trách kiểm tra thực tế và xác minh nội dung phản ánh.',
                    ],
                    [
                        'title' => 'Bước 4: Phối hợp giải quyết',
                        'desc' => 'Các bộ phận liên quan thực hiện xử lý theo chức năng và thẩm quyền.',
                    ],
                ];
            }
        }
    }

    public function addReceptionRule()
    {
        $this->receptionRules[] = '';
    }

    public function removeReceptionRule(int $index)
    {
        if (isset($this->receptionRules[$index])) {
            unset($this->receptionRules[$index]);
            $this->receptionRules = array_values($this->receptionRules);
        }
    }

    public function addFeedbackStep()
    {
        $count = count($this->feedbackProcessSteps) + 1;
        $this->feedbackProcessSteps[] = [
            'title' => "Bước {$count}: ",
            'desc' => '',
        ];
    }

    public function removeFeedbackStep(int $index)
    {
        if (isset($this->feedbackProcessSteps[$index])) {
            unset($this->feedbackProcessSteps[$index]);
            $this->feedbackProcessSteps = array_values($this->feedbackProcessSteps);
        }
    }

    public function addWasteCategory()
    {
        $count = count($this->wasteCategories) + 1;
        $this->wasteCategories[] = [
            'title' => "{$count}. Nhóm rác mới",
            'desc' => '',
            'icon' => 'recycling',
            'theme' => 'emerald',
        ];
    }

    public function removeWasteCategory(int $index)
    {
        if (isset($this->wasteCategories[$index])) {
            unset($this->wasteCategories[$index]);
            $this->wasteCategories = array_values($this->wasteCategories);
            unset($this->wasteCategoryUploads[$index]);
        }
    }

    public function deleteWasteCategoryImage(int $index)
    {
        if (isset($this->wasteCategories[$index])) {
            $this->wasteCategories[$index]['image'] = '';
            unset($this->wasteCategoryUploads[$index]);
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->editingKey = null;
        $this->bgUpload = null;
        $this->wasteCategoryUploads = [];
    }

    public function saveBanner()
    {
        if (!$this->editingKey || !isset($this->banners[$this->editingKey])) {
            return;
        }

        $key = $this->editingKey;

        // Handle banner background image upload
        if ($this->bgUpload) {
            $filename = 'subpage_banner_' . $key . '_' . time() . '.' . $this->bgUpload->getClientOriginalExtension();
            $path = $this->bgUpload->storeAs('banners', $filename, 'public');
            $this->editBgImage = '/storage/' . $path;
        }

        $this->banners[$key]['badge_text'] = $this->editBadgeText;
        $this->banners[$key]['badge_icon'] = $this->editBadgeIcon;
        $this->banners[$key]['title'] = $this->editTitle;
        $this->banners[$key]['subtitle'] = $this->editSubtitle;
        $this->banners[$key]['bg_image'] = $this->editBgImage;

        // Persist banner settings to database
        Setting::updateOrCreate(
            ['key' => 'subpage_banners'],
            [
                'name' => 'Cấu hình Banner các Menu con',
                'value' => json_encode($this->banners, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                'label' => 'Subpage Banners JSON',
                'group' => 'banners',
                'sort_order' => 10,
            ]
        );

        // If editing citizen_reception, also persist rules
        if ($key === 'citizen_reception') {
            $cleanRules = array_values(array_filter(array_map('trim', $this->receptionRules), fn($r) => !empty($r)));
            if (empty($cleanRules)) {
                $cleanRules = [
                    'Xuất trình giấy tờ tùy thân (Căn cước công dân hoặc VNeID Mức 2) khi vào phòng tiếp dân.',
                    'Trình bày nội dung rõ ràng, trung thực và cung cấp chứng cứ, tài liệu liên quan đến vụ việc.',
                    'Giữ gìn trật tự, trang phục lịch sự, chấp hành hướng dẫn của cán bộ tiếp dân.',
                    'Nghiêm cấm mang theo vũ khí, chất cháy nổ hoặc các vật dụng gây nguy hiểm vào trụ sở.',
                ];
            }
            Setting::updateOrCreate(
                ['key' => 'citizen_reception_rules'],
                [
                    'name' => 'Nội quy tiếp công dân',
                    'value' => json_encode($cleanRules, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                    'label' => 'Citizen Reception Rules',
                    'group' => 'citizen_reception',
                    'sort_order' => 5,
                    'is_visible' => 1,
                ]
            );
        }

        // If editing waste_schedule, also persist waste classification guide
        if ($key === 'waste_schedule') {
            // Handle uploaded images for waste categories
            foreach ($this->wasteCategoryUploads as $cIdx => $uploadedFile) {
                if ($uploadedFile && isset($this->wasteCategories[$cIdx])) {
                    $filename = 'waste_cat_' . $cIdx . '_' . time() . '.' . $uploadedFile->getClientOriginalExtension();
                    $path = $uploadedFile->storeAs('waste', $filename, 'public');
                    $this->wasteCategories[$cIdx]['image'] = '/storage/' . $path;
                }
            }
            $this->wasteCategoryUploads = [];

            $guideData = [
                'title' => trim($this->wasteGuideTitle) ?: 'Hướng dẫn phân loại rác tại nguồn',
                'subtitle' => trim($this->wasteGuideSubtitle) ?: 'Thực hiện Luật Bảo vệ môi trường — Chung tay xây dựng Phường Duy Hà Xanh - Sạch - Văn minh',
                'categories' => array_values(array_filter($this->wasteCategories, fn($c) => !empty(trim($c['title'] ?? '')))),
                'regulation' => trim($this->wasteRegulation) ?: 'Bỏ rác đúng giờ trước khi xe đến 15-30 phút. Hành vi vứt rác bừa bãi ra vỉa hè, lòng đường bị phạt tiền từ 500.000đ — 2.000.000đ theo Nghị định 45/2022/NĐ-CP.',
            ];

            Setting::updateOrCreate(
                ['key' => 'waste_classification_guide'],
                [
                    'name' => 'Hướng dẫn phân loại rác',
                    'value' => json_encode($guideData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                    'label' => 'Waste Classification Guide',
                    'group' => 'waste_schedule',
                    'sort_order' => 5,
                    'is_visible' => 1,
                ]
            );
        }

        // If editing feedback, also persist feedback process guide
        if ($key === 'feedback') {
            $cleanSteps = array_values(array_filter($this->feedbackProcessSteps, fn($s) => !empty(trim($s['title'] ?? ''))));
            if (empty($cleanSteps)) {
                $cleanSteps = [
                    ['title' => 'Bước 1: Tiếp nhận phản ánh', 'desc' => 'Người dân gửi thông tin phản ánh qua hệ thống.'],
                    ['title' => 'Bước 2: Phân loại và chuyển xử lý', 'desc' => 'Phản ánh được phân loại và chuyển đến bộ phận phụ trách.'],
                    ['title' => 'Bước 3: Kiểm tra, xác minh', 'desc' => 'Cán bộ phụ trách kiểm tra thực tế và xác minh nội dung phản ánh.'],
                    ['title' => 'Bước 4: Phối hợp giải quyết', 'desc' => 'Các bộ phận liên quan thực hiện xử lý theo chức năng và thẩm quyền.'],
                ];
            }

            $feedbackData = [
                'title' => trim($this->feedbackProcessTitle) ?: 'QUY TRÌNH 4 BƯỚC TIẾP NHẬN',
                'steps' => $cleanSteps,
            ];

            Setting::updateOrCreate(
                ['key' => 'feedback_process_guide'],
                [
                    'name' => 'Quy trình tiếp nhận phản ánh kiến nghị',
                    'value' => json_encode($feedbackData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                    'label' => 'Feedback Process Guide',
                    'group' => 'feedback',
                    'sort_order' => 5,
                    'is_visible' => 1,
                ]
            );
        }

        $this->dumpData();
        $this->closeModal();

        Notification::make()
            ->title("Đã lưu Cấu hình menu '{$this->banners[$key]['page_name']}' thành công!")
            ->success()
            ->send();
    }

    public function resetToDefault(string $key)
    {
        $defaults = $this->defaultBanners();
        if (isset($defaults[$key])) {
            $this->banners[$key] = $defaults[$key];

            Setting::updateOrCreate(
                ['key' => 'subpage_banners'],
                [
                    'name' => 'Cấu hình Banner các Menu con',
                    'value' => json_encode($this->banners, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                    'label' => 'Subpage Banners JSON',
                    'group' => 'banners',
                    'sort_order' => 10,
                ]
            );

            $this->dumpData();

            Notification::make()
                ->title("Đã khôi phục mặc định Banner menu '{$defaults[$key]['page_name']}'")
                ->success()
                ->send();
        }
    }

    private function dumpData()
    {
        $scriptPath = base_path('dump_to_json.php');
        if (file_exists($scriptPath)) {
            @exec("php {$scriptPath}");
        }
    }
}
