<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageSubpageBanners;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ManageSubpageBannersTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_jfif_banner_is_saved_and_exposed_through_the_api_storage_path(): void
    {
        Storage::fake('public');

        Livewire::test(ManageSubpageBanners::class)
            ->call('openEditModal', 'procedures')
            ->set('bgUpload', UploadedFile::fake()->image('banner.jfif', 1600, 500))
            ->call('saveBanner')
            ->assertHasNoErrors();

        $banners = json_decode((string) Setting::where('key', 'subpage_banners')->value('value'), true);
        $imagePath = $banners['procedures']['bg_image'] ?? '';

        $this->assertStringStartsWith('/api/storage/banners/subpage_banner_procedures_', $imagePath);
        Storage::disk('public')->assertExists(substr($imagePath, strlen('/api/storage/')));
    }
}

