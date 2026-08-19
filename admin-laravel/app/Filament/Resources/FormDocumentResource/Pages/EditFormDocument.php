<?php

namespace App\Filament\Resources\FormDocumentResource\Pages;

use App\Filament\Resources\FormDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFormDocument extends EditRecord
{
    protected static string $resource = FormDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
