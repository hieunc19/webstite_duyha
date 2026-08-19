<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageFeedback extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationLabel = 'Phản ánh & Kiến nghị';

    protected static ?string $title = 'Quản lý Tiếp nhận Phản ánh (Google Form)';

    public static function getNavigationGroup(): ?string
    {
        return 'Quản lý Nội dung';
    }

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.manage-feedback';

    public $googleSheetUrl = '';
    public $googleFormUrl = '';

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $formUrlSetting = Setting::where('key', 'feedback_google_form_url')->first();
        if ($formUrlSetting) {
            $this->googleFormUrl = $formUrlSetting->value ?? '';
        }

        $sheetUrlSetting = Setting::where('key', 'feedback_google_sheet_url')->first();
        if ($sheetUrlSetting) {
            $this->googleSheetUrl = $sheetUrlSetting->value ?? '';
        }
    }

    public function save()
    {
        // 1. Save google_form_url
        $formSetting = Setting::firstOrNew(['key' => 'feedback_google_form_url']);
        $formSetting->name = 'Link Google Form Phản ánh kiến nghị';
        $formSetting->value = trim($this->googleFormUrl);
        $formSetting->label = 'Đường link Google Form';
        $formSetting->group = 'feedback';
        $formSetting->sort_order = 1;
        $formSetting->is_visible = 1;
        $formSetting->save();

        // 2. Save google_sheet_url
        $sheetSetting = Setting::firstOrNew(['key' => 'feedback_google_sheet_url']);
        $sheetSetting->name = 'Link Google Sheets xem kết quả';
        $sheetSetting->value = trim($this->googleSheetUrl);
        $sheetSetting->label = 'Đường link Google Sheets';
        $sheetSetting->group = 'feedback';
        $sheetSetting->sort_order = 2;
        $sheetSetting->is_visible = 1;
        $sheetSetting->save();

        // Clear cached Google Form entry mapping
        \Illuminate\Support\Facades\Cache::forget('gf_entries_' . md5(trim($this->googleFormUrl)));

        $this->dumpData();

        Notification::make()
            ->title('Đã lưu cấu hình thành công! Dữ liệu sẽ tự động đẩy vào Google Form này.')
            ->success()
            ->send();
    }

    private function dumpData()
    {
        $scriptPath = base_path('dump_to_json.php');
        if (file_exists($scriptPath)) {
            @exec("php {$scriptPath}");
        }
    }
}
