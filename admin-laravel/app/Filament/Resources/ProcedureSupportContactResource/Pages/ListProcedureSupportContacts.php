<?php

namespace App\Filament\Resources\ProcedureSupportContactResource\Pages;

use App\Filament\Resources\ProcedureSupportContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProcedureSupportContacts extends ListRecords
{
    protected static string $resource = ProcedureSupportContactResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()->label('Thêm liên hệ')];
    }
}
