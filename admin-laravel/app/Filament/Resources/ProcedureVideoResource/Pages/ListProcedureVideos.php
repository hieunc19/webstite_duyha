<?php

namespace App\Filament\Resources\ProcedureVideoResource\Pages;

use App\Filament\Resources\ProcedureVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProcedureVideos extends ListRecords
{
    protected static string $resource = ProcedureVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Thêm video mới'),
        ];
    }
}
