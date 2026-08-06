<?php

namespace App\Filament\Resources\MeritoriousFamilies\Pages;

use App\Filament\Resources\MeritoriousFamilies\MeritoriousFamilyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMeritoriousFamilies extends ListRecords
{
    protected static string $resource = MeritoriousFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
