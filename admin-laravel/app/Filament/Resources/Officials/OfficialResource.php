<?php

namespace App\Filament\Resources\Officials;

use App\Filament\Resources\Officials\Pages\CreateOfficial;
use App\Filament\Resources\Officials\Pages\EditOfficial;
use App\Filament\Resources\Officials\Pages\ListOfficials;
use App\Filament\Resources\Officials\Schemas\OfficialForm;
use App\Filament\Resources\Officials\Tables\OfficialsTable;
use App\Models\Official;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class OfficialResource extends Resource
{
    protected static ?string $model = Official::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Cán bộ & CSKV';

    protected static ?string $pluralLabel = 'Cán bộ & CSKV';

    protected static ?string $modelLabel = 'Cán bộ CSKV';

    public static function getNavigationGroup(): ?string
    {
        return 'Quản lý địa bàn';
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return OfficialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OfficialsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOfficials::route('/'),
            'create' => CreateOfficial::route('/create'),
            'edit' => EditOfficial::route('/{record}/edit'),
        ];
    }
}
