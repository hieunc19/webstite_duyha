<?php

namespace App\Filament\Resources\ProcedureVideoResource\Pages;

use App\Filament\Resources\ProcedureVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcedureVideo extends EditRecord
{
    protected static string $resource = ProcedureVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $scriptPath = base_path('dump_to_json.php');
        if (file_exists($scriptPath)) {
            @exec("php {$scriptPath}");
        }
    }
}
