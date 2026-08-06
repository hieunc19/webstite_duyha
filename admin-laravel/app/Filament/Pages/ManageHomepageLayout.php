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

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $navigationLabel = 'Giao diện Trang chủ';

    protected static ?string $title = 'Quản lý Giao diện Trực quan Trang chủ';

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

    // Hero banner & TDP Merger modal properties
    public $isHeroModal = false;
    public $isTdpMergerModal = false;
    public $logoDoanUrl = '/logo-doan.png';
    public $logoDoanUpload = null;
    public $logoThanhNienUrl = '/logo-thanh-nien.jpg';
    public $logoThanhNienUpload = null;
    public $oldTableTitle = 'TRƯỚC SÁP NHẬP (16 TỔ DÂN PHỐ CỦ)';
    public $newTableTitle = 'SAU SÁP NHẬP (10 TỔ DÂN PHỐ MỚI)';

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

    public function mount()
    {
        $this->loadSections();
        $this->loadStatsData();
    }

    public function loadSections()
    {
        $this->sections = HomepageSection::orderBy('sort_order', 'asc')->get()->toArray();
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

    public function toggleVisibility($id)
    {
        $sec = HomepageSection::find($id);
        if ($sec) {
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
            $this->editingSectionId = $sec->id;
            $this->customTitle = $sec->custom_title ?? '';
            $this->customSubtitle = $sec->custom_subtitle ?? '';
            $settings = $sec->settings ?? [];
            
            $this->isHeaderModal = ($sec->section_code === 'header_navbar');
            $this->isHeroModal = ($sec->section_code === 'hero_banner');
            $this->isStatsModal = ($sec->section_code === 'stats_cards');
            $this->isTdpMergerModal = ($sec->section_code === 'tdp_merger');

            if ($this->isHeaderModal) {
                $this->siteLogo = $settings['site_logo'] ?? '/logo.jpg';
                $this->navHomeLabel = $settings['nav_home_label'] ?? 'Trang chủ';
                $this->navMeritoriousLabel = $settings['nav_meritorious_label'] ?? 'Gia đình có công';
                $this->navMapLabel = $settings['nav_map_label'] ?? 'Bản đồ Duy Hà';
                $this->adminBtnLabel = $settings['admin_btn_label'] ?? 'Quản trị';
            } elseif ($this->isHeroModal) {
                $this->logoDoanUrl = $settings['logo_doan_url'] ?? '/logo-doan.png';
                $this->logoThanhNienUrl = $settings['logo_thanh_nien_url'] ?? '/logo-thanh-nien.jpg';
            } elseif ($this->isStatsModal) {
                $this->loadStatsData();
            } elseif ($this->isTdpMergerModal) {
                $this->oldTableTitle = $settings['old_table_title'] ?? 'TRƯỚC SÁP NHẬP (16 TỔ DÂN PHỐ CỦ)';
                $this->newTableTitle = $settings['new_table_title'] ?? 'SAU SÁP NHẬP (10 TỔ DÂN PHỐ MỚI)';
            }

            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingSectionId = null;
        $this->isHeaderModal = false;
        $this->isHeroModal = false;
        $this->isStatsModal = false;
        $this->isTdpMergerModal = false;
        $this->logoUpload = null;
        $this->logoDoanUpload = null;
        $this->logoThanhNienUpload = null;
    }

    public function saveSection()
    {
        if ($this->editingSectionId) {
            $sec = HomepageSection::find($this->editingSectionId);
            if ($sec) {
                $sec->custom_title = $this->customTitle;
                $sec->custom_subtitle = $this->customSubtitle;

                if ($sec->section_code === 'header_navbar') {
                    $newLogoUrl = $this->saveUploadedFile($this->logoUpload, 'site_logo');
                    if ($newLogoUrl) {
                        $this->siteLogo = $newLogoUrl;
                    }

                    $sec->settings = [
                        'site_logo' => $this->siteLogo ?: '/logo.jpg',
                        'nav_home_label' => $this->navHomeLabel ?: 'Trang chủ',
                        'nav_home_show' => true,
                        'nav_meritorious_label' => $this->navMeritoriousLabel ?: 'Gia đình có công',
                        'nav_meritorious_show' => true,
                        'nav_map_label' => $this->navMapLabel ?: 'Bản đồ Duy Hà',
                        'nav_map_show' => true,
                        'admin_btn_label' => $this->adminBtnLabel ?: 'Quản trị',
                        'admin_btn_show' => true,
                    ];
                } elseif ($sec->section_code === 'hero_banner') {
                    $newDoan = $this->saveUploadedFile($this->logoDoanUpload, 'logo_doan');
                    if ($newDoan) {
                        $this->logoDoanUrl = $newDoan;
                    }

                    $newThanhNien = $this->saveUploadedFile($this->logoThanhNienUpload, 'logo_thanh_nien');
                    if ($newThanhNien) {
                        $this->logoThanhNienUrl = $newThanhNien;
                    }

                    $sec->settings = [
                        'logo_doan_url' => $this->logoDoanUrl ?: '/logo-doan.png',
                        'logo_thanh_nien_url' => $this->logoThanhNienUrl ?: '/logo-thanh-nien.jpg',
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

    private function saveUploadedFile($file, $prefix = 'logo')
    {
        if (!$file) return null;
        try {
            $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
            $filename = $prefix . '_' . time() . '_' . rand(1000, 9999) . '.' . $ext;

            $storageDir = storage_path('app/public/logos');
            $publicDir = public_path('storage/logos');
            $rootPublicDir = base_path('../public/storage/logos');

            if (!file_exists($storageDir)) @mkdir($storageDir, 0777, true);
            if (!file_exists($publicDir)) @mkdir($publicDir, 0777, true);
            if (!file_exists($rootPublicDir)) @mkdir($rootPublicDir, 0777, true);

            $file->storeAs('logos', $filename, 'public');

            @copy($storageDir . '/' . $filename, $publicDir . '/' . $filename);
            @copy($storageDir . '/' . $filename, $rootPublicDir . '/' . $filename);

            return '/storage/logos/' . $filename;
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
}
