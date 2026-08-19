<?php

namespace App\Filament\Resources\FormDocumentResource\Pages;

use App\Filament\Resources\FormDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFormDocuments extends ListRecords
{
    protected static string $resource = FormDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
