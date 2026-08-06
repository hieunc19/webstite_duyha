<?php

namespace App\Filament\Resources\TdpOfficials\Pages;

use App\Filament\Resources\TdpOfficials\TdpOfficialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTdpOfficial extends EditRecord
{
    protected static string $resource = TdpOfficialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
