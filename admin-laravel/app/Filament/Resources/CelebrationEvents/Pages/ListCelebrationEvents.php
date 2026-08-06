<?php

namespace App\Filament\Resources\CelebrationEvents\Pages;

use App\Filament\Resources\CelebrationEvents\CelebrationEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCelebrationEvents extends ListRecords
{
    protected static string $resource = CelebrationEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
