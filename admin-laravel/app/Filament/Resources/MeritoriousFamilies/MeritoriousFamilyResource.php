<?php

namespace App\Filament\Resources\MeritoriousFamilies;

use App\Filament\Resources\MeritoriousFamilies\Pages\CreateMeritoriousFamily;
use App\Filament\Resources\MeritoriousFamilies\Pages\EditMeritoriousFamily;
use App\Filament\Resources\MeritoriousFamilies\Pages\ListMeritoriousFamilies;
use App\Filament\Resources\MeritoriousFamilies\Schemas\MeritoriousFamilyForm;
use App\Filament\Resources\MeritoriousFamilies\Tables\MeritoriousFamiliesTable;
use App\Models\MeritoriousFamily;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MeritoriousFamilyResource extends Resource
{
    protected static ?string $model = MeritoriousFamily::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'Gia đình Có công';

    protected static ?string $pluralLabel = 'Gia đình Có công';

    protected static ?string $modelLabel = 'Gia đình Có công';

    public static function getNavigationGroup(): ?string
    {
        return 'Quản lý Địa bàn & Dân cư';
    }

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MeritoriousFamilyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MeritoriousFamiliesTable::configure($table);
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
            'index' => ListMeritoriousFamilies::route('/'),
            'create' => CreateMeritoriousFamily::route('/create'),
            'edit' => EditMeritoriousFamily::route('/{record}/edit'),
        ];
    }
}
