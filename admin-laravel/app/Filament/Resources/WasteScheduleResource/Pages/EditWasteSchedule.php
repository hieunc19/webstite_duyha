<?php

namespace App\Filament\Resources\WasteScheduleResource\Pages;

use App\Filament\Resources\WasteScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWasteSchedule extends EditRecord
{
    protected static string $resource = WasteScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $scriptPath = base_path('dump_to_json.php');
        if (file_exists($scriptPath)) {
            @exec("php {$scriptPath}");
        }
    }
}
