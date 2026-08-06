<?php

namespace App\Filament\Resources\TdpOfficials;

use App\Filament\Resources\TdpOfficials\Pages\CreateTdpOfficial;
use App\Filament\Resources\TdpOfficials\Pages\EditTdpOfficial;
use App\Filament\Resources\TdpOfficials\Pages\ListTdpOfficials;
use App\Filament\Resources\TdpOfficials\Schemas\TdpOfficialForm;
use App\Filament\Resources\TdpOfficials\Tables\TdpOfficialsTable;
use App\Models\TdpOfficial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class TdpOfficialResource extends Resource
{
    protected static ?string $model = TdpOfficial::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Cán bộ Tổ dân phố (Excel)';

    protected static ?string $pluralLabel = 'Cán bộ Tổ dân phố';

    protected static ?string $modelLabel = 'Cán bộ Tổ dân phố';

    public static function getNavigationGroup(): ?string
    {
        return 'Quản lý địa bàn';
    }

    protected static ?string $recordTitleAttribute = 'tdp_name';

    public static function form(Schema $schema): Schema
    {
        return TdpOfficialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TdpOfficialsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTdpOfficials::route('/'),
            'create' => CreateTdpOfficial::route('/create'),
            'edit' => EditTdpOfficial::route('/{record}/edit'),
        ];
    }
}
