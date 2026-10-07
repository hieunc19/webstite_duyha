<?php

namespace App\Filament\Resources\ProcedureSupportContactResource\Pages;

use App\Filament\Resources\ProcedureSupportContactResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcedureSupportContact extends EditRecord
{
    protected static string $resource = ProcedureSupportContactResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function afterSave(): void
    {
        $scriptPath = base_path('dump_to_json.php');
        if (file_exists($scriptPath)) {
            @exec('php ' . escapeshellarg($scriptPath));
        }
    }
}
