<?php

namespace App\Filament\Resources\AdministrativeUnits\Pages;

use App\Filament\Resources\AdministrativeUnits\AdministrativeUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdministrativeUnits extends ListRecords
{
    protected static string $resource = AdministrativeUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
