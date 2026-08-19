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
}
