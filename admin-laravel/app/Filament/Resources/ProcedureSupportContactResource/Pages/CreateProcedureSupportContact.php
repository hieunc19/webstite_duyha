<?php

namespace App\Filament\Resources\ProcedureSupportContactResource\Pages;

use App\Filament\Resources\ProcedureSupportContactResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProcedureSupportContact extends CreateRecord
{
    protected static string $resource = ProcedureSupportContactResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        $scriptPath = base_path('dump_to_json.php');
        if (file_exists($scriptPath)) {
            @exec('php ' . escapeshellarg($scriptPath));
        }
    }
}
