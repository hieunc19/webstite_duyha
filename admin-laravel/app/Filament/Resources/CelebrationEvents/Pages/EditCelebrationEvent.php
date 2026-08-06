<?php

namespace App\Filament\Resources\CelebrationEvents\Pages;

use App\Filament\Resources\CelebrationEvents\CelebrationEventResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCelebrationEvent extends EditRecord
{
    protected static string $resource = CelebrationEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
