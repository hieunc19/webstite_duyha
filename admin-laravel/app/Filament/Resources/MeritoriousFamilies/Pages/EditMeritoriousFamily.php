<?php

namespace App\Filament\Resources\MeritoriousFamilies\Pages;

use App\Filament\Resources\MeritoriousFamilies\MeritoriousFamilyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMeritoriousFamily extends EditRecord
{
    protected static string $resource = MeritoriousFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
