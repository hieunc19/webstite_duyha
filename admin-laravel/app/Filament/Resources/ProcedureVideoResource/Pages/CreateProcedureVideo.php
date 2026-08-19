<?php

namespace App\Filament\Resources\ProcedureVideoResource\Pages;

use App\Filament\Resources\ProcedureVideoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProcedureVideo extends CreateRecord
{
    protected static string $resource = ProcedureVideoResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        $scriptPath = base_path('dump_to_json.php');
        if (file_exists($scriptPath)) {
            @exec("php {$scriptPath}");
        }
    }
}
