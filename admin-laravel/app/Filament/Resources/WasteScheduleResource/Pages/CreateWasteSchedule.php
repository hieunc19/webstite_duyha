<?php

namespace App\Filament\Resources\WasteScheduleResource\Pages;

use App\Filament\Resources\WasteScheduleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWasteSchedule extends CreateRecord
{
    protected static string $resource = WasteScheduleResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        $scriptPath = base_path('dump_to_json.php');
        if (file_exists($scriptPath)) {
            @exec("php {$scriptPath}");
        }
    }
}
