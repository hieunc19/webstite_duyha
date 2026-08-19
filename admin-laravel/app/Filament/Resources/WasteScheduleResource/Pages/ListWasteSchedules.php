<?php

namespace App\Filament\Resources\WasteScheduleResource\Pages;

use App\Filament\Resources\WasteScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWasteSchedules extends ListRecords
{
    protected static string $resource = WasteScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
