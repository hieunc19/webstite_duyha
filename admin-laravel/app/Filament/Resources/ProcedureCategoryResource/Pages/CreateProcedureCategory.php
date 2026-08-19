<?php

namespace App\Filament\Resources\ProcedureCategoryResource\Pages;

use App\Filament\Resources\ProcedureCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProcedureCategory extends CreateRecord
{
    protected static string $resource = ProcedureCategoryResource::class;

    protected static bool $canCreateAnother = false;
}
