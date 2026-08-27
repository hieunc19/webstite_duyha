<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class ManageCitizenReception extends Page
{
    use WithFileUploads;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Lịch tiếp công dân';

    protected static ?string $title = 'Quản lý File Lịch Tiếp công dân (Ảnh / PDF)';

    public static function getNavigationGroup(): ?string
    {
        return 'Quản lý Nội dung';
    }

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.manage-citizen-reception';

    public $currentImage = '';
    public $imageFile;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->currentImage = Setting::where('key', 'citizen_reception_image')->value('value') ?? '';
    }

    public function getIsCurrentPdfProperty(): bool
    {
        return !empty($this->currentImage) && str_ends_with(strtolower($this->currentImage), '.pdf');
    }

    public function getIsUploadedPdfProperty(): bool
    {
        if (!$this->imageFile) return false;
        $ext = strtolower($this->imageFile->getClientOriginalExtension());
        return $ext === 'pdf';
    }

    public function getCurrentImageUrlProperty(): ?string
    {
        if (empty($this->currentImage)) {
            return null;
        }

        if (str_starts_with($this->currentImage, 'http')) {
            return $this->currentImage;
        }

        return url('/api/storage/' . $this->currentImage);
    }

    public function deleteImage()
    {
        if (!empty($this->currentImage) && !str_starts_with($this->currentImage, 'http')) {
            Storage::disk('public')->delete($this->currentImage);
        }

        $this->currentImage = '';
        $this->imageFile = null;

        Setting::updateOrCreate(
            ['key' => 'citizen_reception_image'],
            ['value' => '']
        );

        $this->dumpData();

        Notification::make()
            ->title('Đã gỡ file lịch tiếp công dân!')
            ->success()
            ->send();
    }

    public function save()
    {
        // 1. Handle uploaded image/pdf file if any
        if ($this->imageFile) {
            $path = $this->imageFile->store('citizen-reception', 'public');
            $this->currentImage = $path;
            $this->imageFile = null;
        }

        // 2. Save settings
        Setting::updateOrCreate(
            ['key' => 'citizen_reception_image'],
            ['name' => 'File Lịch tiếp công dân (Ảnh / PDF)', 'value' => trim($this->currentImage), 'label' => 'Tệp lịch tiếp dân', 'group' => 'citizen_reception', 'sort_order' => 1, 'is_visible' => 1]
        );

        $this->dumpData();

        Notification::make()
            ->title('Đã cập nhật File Lịch tiếp công dân thành công!')
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
