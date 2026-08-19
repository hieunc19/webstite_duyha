<?php

namespace App\Filament\Resources\ProcedureResource\Pages;

use App\Filament\Resources\ProcedureResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProcedure extends CreateRecord
{
    protected static string $resource = ProcedureResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        $scriptPath = base_path('dump_to_json.php');
        if (file_exists($scriptPath)) {
            @exec("php {$scriptPath}");
        }
    }
}
