<?php

namespace App\Filament\Resources\FormDocumentResource\Pages;

use App\Filament\Resources\FormDocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFormDocument extends CreateRecord
{
    protected static string $resource = FormDocumentResource::class;

    protected static bool $canCreateAnother = false;
}
