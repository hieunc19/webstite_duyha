<?php

namespace App\Filament\Resources\AdministrativeUnits\Pages;

use App\Filament\Resources\AdministrativeUnits\AdministrativeUnitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdministrativeUnit extends EditRecord
{
    protected static string $resource = AdministrativeUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
