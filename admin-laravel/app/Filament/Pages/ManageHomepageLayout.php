<?php

namespace App\Filament\Pages;

use App\Models\HomepageSection;
use App\Models\Setting;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\WithFileUploads;

class ManageHomepageLayout extends Page
{
    use WithFileUploads;

    private const MANAGED_HOMEPAGE_SECTION_CODES = [
        'header_navbar',
        'hero_banner',
        'stats_cards',
        'agencies_grid',
        'procedures_utilities',
        'hdsd_procedure',
        'footer_section',
    ];

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $navigationLabel = 'Giao diện Trang chủ';

    protected static ?string $title = 'Quản lý Giao diện Trực quan Trang chủ & Menu (Page Builder)';

    public static function getNavigationGroup(): ?string
    {
        return 'Cài đặt';
    }

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.manage-homepage-layout';

    public $sections = [];

    // Edit modal properties
    public $editingSectionId = null;
    public $customTitle = '';
    public $customSubtitle = '';
    public $showModal = false;

    // Header Navbar specific properties
    public $isHeaderModal = false;
    public $siteLogo = '/logo.jpg';
    public $logoUpload = null;
    public $navHomeLabel = 'Trang chủ';
    public $navMeritoriousLabel = 'Gia đình có công';
    public $navMapLabel = 'Bản đồ Duy Hà';
    public $adminBtnLabel = 'Quản trị';
    public $navMenuItems = [];

    // Hero banner & TDP Merger modal properties
    public $isHeroModal = false;
    public $isTdpMergerModal = false;
    public $logoDoanUrl = '/logo-doan.png';
    public $logoDoanUpload = null;
    public $heroBgType = 'image'; // 'image' or 'video'
    public $heroBgUrl = '/hero-bg.jpg';
    public $heroBgUpload = null;
    public $heroVideoUrl = '';
    public $heroVideoUpload = null;
    public $heroHeight = 'standard'; // 'compact', 'standard', 'cinematic', 'auto_16_9'
    public $heroFit = 'cover'; // 'cover', 'contain'
    public $heroPosition = 'center'; // 'center', 'top', 'bottom'
    public $oldTableTitle = 'TRƯỚC SÁP NHẬP (16 TỔ DÂN PHỐ CỦ)';
    public $newTableTitle = 'SAU SÁP NHẬP (10 TỔ DÂN PHỐ MỚI)';

    // Agencies grid modal properties
    public $isAgenciesModal = false;
    public $selectedAgencyIds = [null, null, null, null];

    // Stats Cards modal properties
    public $isStatsModal = false;
    public $stat1Val = '10';
    public $stat1Lbl = 'Tổ dân phố';
    public $stat2Val = '6.767';
    public $stat2Lbl = 'Hộ gia đình';
    public $stat3Val = '23.615';
    public $stat3Lbl = 'Nhân khẩu';
    public $stat4Val = '15,46 km²';
    public $stat4Lbl = 'Diện tích địa bàn';

    // Custom Section properties
    public $isCustomSectionModal = false;
    public $customSectionContent = '';
    public $customSectionBtnText = '';
    public $customSectionBtnUrl = '';
    public $customSectionBadge = '';

    // Footer section properties
    public $isFooterModal = false;
    public $footerLogo = '/logo.jpg';
    public $footerLogoUpload = null;
    public $footerOrgName = 'ĐOÀN TNCS HỒ CHÍ MINH PHƯỜNG DUY HÀ';
    public $footerAddress = 'Số 01 đường Lê Lợi, Phường Duy Hà, thành phố Ninh Bình, tỉnh Ninh Bình';
    public $footerWorkingHours = 'Sáng: 7h30 - 11h30 | Chiều: 13h30 - 17h00 (Từ Thứ 2 đến Thứ 6, nghỉ T7 & CN)';
    public $footerEmail = 'thongtin@duyha.ninhbinh.gov.vn';
    public $footerPhone = '(0229) 38253536';
    public $footerFacebookUrl = 'https://facebook.com';
    public $footerWebsiteUrl = 'https://duyha.ninhbinh.gov.vn';
    public $footerCopyright = 'Copyright © Đoàn TNCS Hồ Chí Minh phường Duy Hà. All Rights Reserved';
    public $footerSourceNote = 'Ghi rõ nguồn "Đoàn TNCS Hồ Chí Minh phường Duy Hà" khi phát hành lại thông tin từ Đoàn TNCS Hồ Chí Minh phường Duy Hà.';

    public function mount()
    {
        $this->loadSections();
        $this->loadStatsData();
    }

    public function loadSections()
    {
        $this->sections = $this->managedHomepageSectionsQuery()
            ->orderBy('sort_order', 'asc')
            ->get()
            ->toArray();
    }

    public function loadStatsData()
    {
        $s1 = Setting::where('key', 'stat_1')->first();
        if ($s1) { $this->stat1Val = $s1->value; $this->stat1Lbl = $s1->label; }

        $s2 = Setting::where('key', 'stat_2')->first();
        if ($s2) { $this->stat2Val = $s2->value; $this->stat2Lbl = $s2->label; }

        $s3 = Setting::where('key', 'stat_3')->first();
        if ($s3) { $this->stat3Val = $s3->value; $this->stat3Lbl = $s3->label; }

        $s4 = Setting::where('key', 'stat_4')->first();
        if ($s4) { $this->stat4Val = $s4->value; $this->stat4Lbl = $s4->label; }
    }

    public function moveUp($id)
    {
        $current = HomepageSection::find($id);
        if (!$current) return;
        if (!$this->isManagedHomepageSection($current->section_code)) return;

        $previous = $this->managedHomepageSectionsQuery()
            ->where('sort_order', '<', $current->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($previous) {
            $temp = $current->sort_order;
            $current->sort_order = $previous->sort_order;
            $previous->sort_order = $temp;
            $current->save();
            $previous->save();

            $this->loadSections();
            $this->dumpData();

            Notification::make()
                ->title("Đã đẩy khối '{$current->name}' lên vị trí #{$current->sort_order}")
                ->success()
                ->send();
        }
    }

    public function moveDown($id)
    {
        $current = HomepageSection::find($id);
        if (!$current) return;
        if (!$this->isManagedHomepageSection($current->section_code)) return;

        $next = $this->managedHomepageSectionsQuery()
            ->where('sort_order', '>', $current->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($next) {
            $temp = $current->sort_order;
            $current->sort_order = $next->sort_order;
            $next->sort_order = $temp;
            $current->save();
            $next->save();

            $this->loadSections();
            $this->dumpData();

            Notification::make()
                ->title("Đã đẩy khối '{$current->name}' xuống vị trí #{$current->sort_order}")
                ->success()
                ->send();
        }
    }

    public function toggleVisibility($id)
    {
        $sec = HomepageSection::find($id);
        if ($sec) {
            if (!$this->isManagedHomepageSection($sec->section_code)) return;

            $sec->is_visible = !$sec->is_visible;
            $sec->save();
            $this->loadSections();
            $this->dumpData();

            Notification::make()
                ->title($sec->is_visible ? "Đã BẬT hiển thị khối '{$sec->name}'" : "Đã ẨN khối '{$sec->name}'")
                ->success()
                ->send();
        }
    }

    public function openEditModal($id)
    {
        $sec = HomepageSection::find($id);
        if ($sec) {
            if (!$this->isManagedHomepageSection($sec->section_code)) return;

            $this->editingSectionId = $sec->id;
            $this->customTitle = $sec->custom_title ?? '';
            $this->customSubtitle = $sec->custom_subtitle ?? '';
            $settings = $sec->settings ?? [];
            
            $this->isHeaderModal = ($sec->section_code === 'header_navbar');
            $this->isHeroModal = ($sec->section_code === 'hero_banner');
            $this->isAgenciesModal = ($sec->section_code === 'agencies_grid');
            $this->isStatsModal = ($sec->section_code === 'stats_cards');
            $this->isTdpMergerModal = ($sec->section_code === 'tdp_merger');
            $this->isFooterModal = ($sec->section_code === 'footer_section');
            $this->isCustomSectionModal = str_starts_with($sec->section_code, 'custom_');

            if ($this->isHeaderModal) {
                $this->siteLogo = $settings['site_logo'] ?? '/logo.jpg';
                $this->navHomeLabel = $settings['nav_home_label'] ?? 'Trang chủ';
                $this->navMeritoriousLabel = $settings['nav_meritorious_label'] ?? 'Gia đình có công';
                $this->navMapLabel = $settings['nav_map_label'] ?? 'Bản đồ số Duy Hà';
                $this->adminBtnLabel = $settings['admin_btn_label'] ?? 'Quản trị';

                // Load dynamic navigation menu items
                if (!empty($settings['menu_items']) && is_array($settings['menu_items'])) {
                    $this->navMenuItems = $this->normalizeMenuItems($settings['menu_items']);
                } else {
                    $this->navMenuItems = $this->defaultMenuItems();
                }
            } elseif ($this->isHeroModal) {
                $this->logoDoanUrl = $settings['logo_doan_url'] ?? '/logo-doan.png';
                $this->heroBgType = $settings['bg_type'] ?? (!empty($settings['hero_video_url']) ? 'video' : 'image');
                $this->heroBgUrl = $settings['hero_bg_url'] ?? '/hero-bg.jpg';
                $this->heroVideoUrl = $settings['hero_video_url'] ?? '';
                $this->heroHeight = $settings['hero_height'] ?? 'standard';
                $this->heroFit = $settings['hero_fit'] ?? 'cover';
                $this->heroPosition = $settings['hero_position'] ?? 'center';
            } elseif ($this->isAgenciesModal) {
                $ids = $settings['selected_ids'] ?? [];
                if (empty($ids) || !is_array($ids)) {
                    $ids = \App\Models\Place::where('category', '!=', 'neighborhood')->orderBy('id')->limit(4)->pluck('id')->toArray();
                }
                while (count($ids) < 4) {
                    $ids[] = null;
                }
                $this->selectedAgencyIds = array_slice($ids, 0, 4);
            } elseif ($this->isStatsModal) {
                $this->loadStatsData();
            } elseif ($this->isTdpMergerModal) {
                $this->oldTableTitle = $settings['old_table_title'] ?? 'TRƯỚC SÁP NHẬP (16 TỔ DÂN PHỐ CỦ)';
                $this->newTableTitle = $settings['new_table_title'] ?? 'SAU SÁP NHẬP (10 TỔ DÂN PHỐ MỚI)';
            } elseif ($this->isFooterModal) {
                $this->footerLogo = $settings['footer_logo'] ?? '/logo.jpg';
                $this->footerOrgName = $settings['org_name'] ?? ($sec->custom_title ?: 'ĐOÀN TNCS HỒ CHÍ MINH PHƯỜNG DUY HÀ');
                $this->footerAddress = $settings['address'] ?? ($sec->custom_subtitle ?: 'Số 01 đường Lê Lợi, Phường Duy Hà, thành phố Ninh Bình, tỉnh Ninh Bình');
                $this->footerWorkingHours = $settings['working_hours'] ?? 'Sáng: 7h30 - 11h30 | Chiều: 13h30 - 17h00 (Từ Thứ 2 đến Thứ 6, nghỉ T7 & CN)';
                $this->footerEmail = $settings['email'] ?? 'thongtin@duyha.ninhbinh.gov.vn';
                $this->footerPhone = $settings['phone'] ?? '(0229) 38253536';
                $this->footerFacebookUrl = $settings['facebook_url'] ?? 'https://facebook.com';
                $this->footerWebsiteUrl = $settings['website_url'] ?? 'https://duyha.ninhbinh.gov.vn';
                $this->footerCopyright = $settings['copyright_text'] ?? 'Copyright © Đoàn TNCS Hồ Chí Minh phường Duy Hà. All Rights Reserved';
                $this->footerSourceNote = $settings['source_note'] ?? 'Ghi rõ nguồn "Đoàn TNCS Hồ Chí Minh phường Duy Hà" khi phát hành lại thông tin từ Đoàn TNCS Hồ Chí Minh phường Duy Hà.';
            } elseif ($this->isCustomSectionModal) {
                $this->customSectionContent = $settings['content'] ?? '';
                $this->customSectionBtnText = $settings['btn_text'] ?? '';
                $this->customSectionBtnUrl = $settings['btn_url'] ?? '';
                $this->customSectionBadge = $settings['badge'] ?? '';
            }

            $this->showModal = true;
        }
    }

    public static function getSystemPages(): array
    {
        return [
            'Trang chính' => [
                '/index.html' => 'Trang chủ',
                '/index.html#map-view' => 'Bản đồ số Duy Hà (Không gian số)',
            ],
            'Dịch vụ công & TTHC' => [
                '/procedures.html' => 'Thủ tục hành chính (Tra cứu TTHC)',
                '/procedures.html?tab=videos' => 'Video hướng dẫn dịch vụ công',
                '/procedures.html?tab=policies' => 'Chính sách & Văn bản quy định',
            ],
            'Tiếp dân & Lịch trình' => [
                '/citizen-reception.html' => 'Lịch tiếp công dân định kỳ',
                '/waste-schedule.html' => 'Lịch thu gom rác sinh hoạt',
            ],
            'Tổ chức & Cán bộ' => [
                '/officials.html' => 'Danh sách Cán bộ Phường',
                '/tdp-merger.html' => 'Tổ dân phố & Cán bộ TDP',
                '/agencies.html' => 'Danh sách Cơ quan hành chính',
            ],
            'An sinh xã hội' => [
                '/meritorious-families.html' => 'Gia đình chính sách & Người có công',
                '/feedback.html' => 'Gửi Phản ánh & Kiến nghị',
            ],
            'Tùy chỉnh khác' => [
                'custom' => '🔗 Liên kết ngoài / Tùy chỉnh URL',
            ],
        ];
    }

    public function onMenuItemPageChange($index, $selectedUrl)
    {
        if (!isset($this->navMenuItems[$index])) return;

        $systemPagesFlat = [
            '/index.html' => 'Trang chủ',
            '/index.html#map-view' => 'Bản đồ số Duy Hà',
            '/procedures.html' => 'Thủ tục hành chính',
            '/procedures.html?tab=videos' => 'Video hướng dẫn',
            '/procedures.html?tab=policies' => 'Chính sách & Quy định',
            '/citizen-reception.html' => 'Lịch tiếp dân',
            '/waste-schedule.html' => 'Lịch thu gom rác',
            '/officials.html' => 'Cán bộ Phường',
            '/tdp-merger.html' => 'Tổ dân phố',
            '/agencies.html' => 'Cơ quan hành chính',
            '/meritorious-families.html' => 'Người có công',
            '/feedback.html' => 'Gửi Phản ánh',
        ];

        if ($selectedUrl !== 'custom') {
            $this->navMenuItems[$index]['url'] = $selectedUrl;
            if (isset($systemPagesFlat[$selectedUrl])) {
                $this->navMenuItems[$index]['label'] = $systemPagesFlat[$selectedUrl];
            }
        } else {
            if (isset($systemPagesFlat[$this->navMenuItems[$index]['url']])) {
                $this->navMenuItems[$index]['url'] = 'https://';
            }
        }
    }

    public function addMenuItem()
    {
        $this->navMenuItems[] = [
            'id' => 'item_' . time() . '_' . rand(100, 999),
            'label' => 'Thủ tục hành chính',
            'url' => '/procedures.html',
            'target' => '_self',
            'is_active' => true,
        ];
    }

    public function removeMenuItem($index)
    {
        if (isset($this->navMenuItems[$index])) {
            unset($this->navMenuItems[$index]);
            $this->navMenuItems = array_values($this->navMenuItems);
        }
    }

    public function moveMenuItemUp($index)
    {
        if ($index > 0 && isset($this->navMenuItems[$index]) && isset($this->navMenuItems[$index - 1])) {
            $temp = $this->navMenuItems[$index];
            $this->navMenuItems[$index] = $this->navMenuItems[$index - 1];
            $this->navMenuItems[$index - 1] = $temp;
        }
    }

    public function moveMenuItemDown($index)
    {
        if ($index < count($this->navMenuItems) - 1 && isset($this->navMenuItems[$index]) && isset($this->navMenuItems[$index + 1])) {
            $temp = $this->navMenuItems[$index];
            $this->navMenuItems[$index] = $this->navMenuItems[$index + 1];
            $this->navMenuItems[$index + 1] = $temp;
        }
    }

    public function createCustomBlock()
    {
        $maxSort = $this->managedHomepageSectionsQuery()->max('sort_order') ?? 0;
        $newSec = HomepageSection::create([
            'section_code' => 'custom_' . time(),
            'name' => 'Khối Banner / Thông báo Tùy chỉnh #' . ($maxSort + 1),
            'custom_title' => 'Tiêu đề thông báo / Banner nổi bật',
            'custom_subtitle' => 'Nội dung mô tả ngắn gọn hoặc thông báo khẩn từ UBND Phường',
            'is_visible' => true,
            'sort_order' => $maxSort + 1,
            'settings' => [
                'content' => 'Nội dung chi tiết thông báo tuyên truyền, sự kiện chính trị - xã hội...',
                'btn_text' => 'Xem chi tiết',
                'btn_url' => '/procedures.html',
                'badge' => 'THÔNG BÁO MỚI',
            ]
        ]);

        $this->loadSections();
        $this->dumpData();

        Notification::make()
            ->title("Đã tạo khối mới '{$newSec->name}'")
            ->success()
            ->send();
    }

    public function deleteCustomBlock($id)
    {
        $sec = HomepageSection::find($id);
        if ($sec && str_starts_with($sec->section_code, 'custom_')) {
            $name = $sec->name;
            $sec->delete();
            $this->loadSections();
            $this->dumpData();

            Notification::make()
                ->title("Đã xóa khối '{$name}'")
                ->success()
                ->send();
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingSectionId = null;
        $this->isHeaderModal = false;
        $this->isHeroModal = false;
        $this->isAgenciesModal = false;
        $this->isStatsModal = false;
        $this->isTdpMergerModal = false;
        $this->isFooterModal = false;
        $this->isCustomSectionModal = false;
        $this->selectedAgencyIds = [null, null, null, null];
        $this->logoUpload = null;
        $this->logoDoanUpload = null;
        $this->heroBgUpload = null;
        $this->heroVideoUpload = null;
        $this->footerLogoUpload = null;
    }

    public function saveSection()
    {
        if ($this->editingSectionId) {
            $sec = HomepageSection::find($this->editingSectionId);
            if ($sec) {
                if (!$this->isManagedHomepageSection($sec->section_code)) return;

                $sec->custom_title = $this->customTitle;
                $sec->custom_subtitle = $this->customSubtitle;

                if ($sec->section_code === 'header_navbar') {
                    $newLogoUrl = $this->saveUploadedFile($this->logoUpload, 'site_logo', 'logos');
                    if ($newLogoUrl) {
                        $this->siteLogo = $newLogoUrl;
                    }

                    $sec->settings = [
                        'site_logo' => $this->siteLogo ?: '/logo.jpg',
                        'nav_home_label' => $this->navHomeLabel ?: 'Trang chủ',
                        'nav_home_show' => true,
                        'nav_map_label' => $this->navMapLabel ?: 'Bản đồ Duy Hà',
                        'nav_map_show' => true,
                        'admin_btn_label' => $this->adminBtnLabel ?: 'Quản trị',
                        'admin_btn_show' => true,
                        'menu_items' => $this->normalizeMenuItems($this->navMenuItems),
                    ];
                } elseif ($sec->section_code === 'hero_banner') {
                    $newDoan = $this->saveUploadedFile($this->logoDoanUpload, 'logo_doan', 'logos');
                    if ($newDoan) {
                        $this->logoDoanUrl = $newDoan;
                    }

                    $newHeroBg = $this->saveUploadedFile($this->heroBgUpload, 'hero_bg', 'banners');
                    if ($newHeroBg) {
                        $this->heroBgUrl = $newHeroBg;
                    }

                    $newHeroVideo = $this->saveUploadedFile($this->heroVideoUpload, 'hero_video', 'banners');
                    if ($newHeroVideo) {
                        $this->heroVideoUrl = $newHeroVideo;
                    }

                    $sec->settings = [
                        'logo_doan_url' => $this->logoDoanUrl ?: '/logo-doan.png',
                        'bg_type' => $this->heroBgType ?: 'image',
                        'hero_bg_url' => $this->heroBgUrl ?: '/hero-bg.jpg',
                        'hero_video_url' => $this->heroVideoUrl ?: '',
                        'hero_height' => $this->heroHeight ?: 'standard',
                        'hero_fit' => $this->heroFit ?: 'cover',
                        'hero_position' => $this->heroPosition ?: 'center',
                    ];
                } elseif ($sec->section_code === 'agencies_grid') {
                    $validIds = array_values(array_filter(array_map('intval', (array)$this->selectedAgencyIds), fn($id) => $id > 0));
                    $sec->settings = [
                        'selected_ids' => $validIds,
                        'display_limit' => count($validIds) ?: 4,
                    ];
                } elseif ($sec->section_code === 'stats_cards') {
                    Setting::updateOrCreate(
                        ['key' => 'stat_1'],
                        ['name' => 'Thẻ 1: Tổng số Tổ dân phố', 'value' => $this->stat1Val, 'label' => $this->stat1Lbl, 'group' => 'stats', 'sort_order' => 1]
                    );
                    Setting::updateOrCreate(
                        ['key' => 'stat_2'],
                        ['name' => 'Thẻ 2: Tổng số Hộ gia đình', 'value' => $this->stat2Val, 'label' => $this->stat2Lbl, 'group' => 'stats', 'sort_order' => 2]
                    );
                    Setting::updateOrCreate(
                        ['key' => 'stat_3'],
                        ['name' => 'Thẻ 3: Tổng số Nhân khẩu', 'value' => $this->stat3Val, 'label' => $this->stat3Lbl, 'group' => 'stats', 'sort_order' => 3]
                    );
                    Setting::updateOrCreate(
                        ['key' => 'stat_4'],
                        ['name' => 'Thẻ 4: Diện tích địa bàn', 'value' => $this->stat4Val, 'label' => $this->stat4Lbl, 'group' => 'stats', 'sort_order' => 4]
                    );

                    $sec->settings = [
                        'stat_1' => ['value' => $this->stat1Val, 'label' => $this->stat1Lbl],
                        'stat_2' => ['value' => $this->stat2Val, 'label' => $this->stat2Lbl],
                        'stat_3' => ['value' => $this->stat3Val, 'label' => $this->stat3Lbl],
                        'stat_4' => ['value' => $this->stat4Val, 'label' => $this->stat4Lbl],
                    ];
                } elseif ($sec->section_code === 'tdp_merger') {
                    $sec->settings = [
                        'old_table_title' => $this->oldTableTitle ?: 'TRƯỚC SÁP NHẬP (16 TỔ DÂN PHỐ CỦ)',
                        'new_table_title' => $this->newTableTitle ?: 'SAU SÁP NHẬP (10 TỔ DÂN PHỐ MỚI)',
                    ];
                } elseif ($sec->section_code === 'footer_section') {
                    $newFooterLogo = $this->saveUploadedFile($this->footerLogoUpload, 'footer_logo', 'logos');
                    if ($newFooterLogo) {
                        $this->footerLogo = $newFooterLogo;
                    }

                    $sec->custom_title = $this->footerOrgName ?: 'ĐOÀN TNCS HỒ CHÍ MINH PHƯỜNG DUY HÀ';
                    $sec->custom_subtitle = $this->footerAddress ?: 'Số 01 đường Lê Lợi, Phường Duy Hà, thành phố Ninh Bình, tỉnh Ninh Bình';

                    $sec->settings = [
                        'footer_logo' => $this->footerLogo ?: '/logo.jpg',
                        'org_name' => $this->footerOrgName ?: 'ĐOÀN TNCS HỒ CHÍ MINH PHƯỜNG DUY HÀ',
                        'address' => $this->footerAddress ?: 'Số 01 đường Lê Lợi, Phường Duy Hà, thành phố Ninh Bình, tỉnh Ninh Bình',
                        'working_hours' => $this->footerWorkingHours ?: 'Sáng: 7h30 - 11h30 | Chiều: 13h30 - 17h00 (Từ Thứ 2 đến Thứ 6, nghỉ T7 & CN)',
                        'email' => $this->footerEmail ?: 'thongtin@duyha.ninhbinh.gov.vn',
                        'phone' => $this->footerPhone ?: '(0229) 38253536',
                        'facebook_url' => $this->footerFacebookUrl ?: 'https://facebook.com',
                        'website_url' => $this->footerWebsiteUrl ?: 'https://duyha.ninhbinh.gov.vn',
                        'copyright_text' => $this->footerCopyright ?: 'Copyright © Đoàn TNCS Hồ Chí Minh phường Duy Hà. All Rights Reserved',
                        'source_note' => $this->footerSourceNote ?: 'Ghi rõ nguồn "Đoàn TNCS Hồ Chí Minh phường Duy Hà" khi phát hành lại thông tin từ Đoàn TNCS Hồ Chí Minh phường Duy Hà.',
                    ];
                } elseif (str_starts_with($sec->section_code, 'custom_')) {
                    $sec->settings = [
                        'content' => $this->customSectionContent,
                        'btn_text' => $this->customSectionBtnText,
                        'btn_url' => $this->customSectionBtnUrl,
                        'badge' => $this->customSectionBadge,
                    ];
                }

                $sec->save();

                $this->loadSections();
                $this->loadStatsData();
                $this->dumpData();
                $this->closeModal();

                Notification::make()
                    ->title("Đã lưu cấu hình khối '{$sec->name}'")
                    ->success()
                    ->send();
            }
        }
    }

    private function saveUploadedFile($file, $prefix = 'logo', $subfolder = 'logos')
    {
        if (!$file) return null;
        try {
            $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
            $filename = $prefix . '_' . time() . '_' . rand(1000, 9999) . '.' . $ext;

            $storageDir = storage_path('app/public/' . $subfolder);
            $publicDir = public_path('storage/' . $subfolder);
            $rootPublicDir = base_path('../public/storage/' . $subfolder);
            $clientPublicDir = base_path('../client/public/storage/' . $subfolder);

            if (!file_exists($storageDir)) @mkdir($storageDir, 0777, true);
            if (!file_exists($publicDir)) @mkdir($publicDir, 0777, true);
            if (!file_exists($rootPublicDir)) @mkdir($rootPublicDir, 0777, true);
            if (!file_exists($clientPublicDir)) @mkdir($clientPublicDir, 0777, true);

            $file->storeAs($subfolder, $filename, 'public');

            @copy($storageDir . '/' . $filename, $publicDir . '/' . $filename);
            @copy($storageDir . '/' . $filename, $rootPublicDir . '/' . $filename);
            @copy($storageDir . '/' . $filename, $clientPublicDir . '/' . $filename);

            return '/storage/' . $subfolder . '/' . $filename;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function dumpData()
    {
        $scriptPath = base_path('dump_to_json.php');
        if (file_exists($scriptPath)) {
            @exec("php {$scriptPath}");
        }
    }

    private function managedHomepageSectionsQuery()
    {
        return HomepageSection::query()
            ->where(function ($query) {
                $query
                    ->whereIn('section_code', self::MANAGED_HOMEPAGE_SECTION_CODES)
                    ->orWhere('section_code', 'like', 'custom_%');
            });
    }

    private function isManagedHomepageSection(?string $sectionCode): bool
    {
        if (!$sectionCode) {
            return false;
        }

        return in_array($sectionCode, self::MANAGED_HOMEPAGE_SECTION_CODES, true)
            || str_starts_with($sectionCode, 'custom_');
    }

    private function defaultMenuItems(): array
    {
        return [
            [
                'id' => 'item_1',
                'label' => 'Trang chủ',
                'url' => '/index.html',
                'target' => '_self',
                'is_active' => true,
            ],
            [
                'id' => 'item_2',
                'label' => 'Thủ tục hành chính',
                'url' => '/procedures.html',
                'target' => '_self',
                'is_active' => true,
            ],
            [
                'id' => 'item_3',
                'label' => 'Bản đồ số Duy Hà',
                'url' => '/index.html#map-view',
                'target' => '_self',
                'is_active' => true,
            ],
        ];
    }

    private function normalizeMenuItems(array $items): array
    {
        return array_values(array_map(function ($item, $index) {
            $target = $item['target'] ?? '_self';

            return [
                'id' => $item['id'] ?? 'item_' . ($index + 1),
                'label' => trim((string) ($item['label'] ?? '')),
                'url' => trim((string) ($item['url'] ?? '/index.html')),
                'target' => ($target === '_blank' || $target === true) ? '_blank' : '_self',
                'is_active' => (bool) ($item['is_active'] ?? true),
            ];
        }, $items, array_keys($items)));
    }
}
