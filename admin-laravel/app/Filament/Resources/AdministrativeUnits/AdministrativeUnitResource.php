<?php

namespace App\Filament\Resources\AdministrativeUnits;

use App\Filament\Resources\AdministrativeUnits\Pages\CreateAdministrativeUnit;
use App\Filament\Resources\AdministrativeUnits\Pages\EditAdministrativeUnit;
use App\Filament\Resources\AdministrativeUnits\Pages\ListAdministrativeUnits;
use App\Filament\Resources\AdministrativeUnits\Schemas\AdministrativeUnitForm;
use App\Filament\Resources\AdministrativeUnits\Tables\AdministrativeUnitsTable;
use App\Models\AdministrativeUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AdministrativeUnitResource extends Resource
{
    protected static ?string $model = AdministrativeUnit::class;

    protected static ?string $navigationLabel = 'Đơn vị Hành chính';
    protected static ?string $breadcrumb = 'Đơn vị Hành chính';
    protected static ?string $modelLabel = 'Đơn vị Hành chính';
    protected static bool $shouldRegisterNavigation = false;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Quản lý Địa bàn & Dân cư';
    }

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AdministrativeUnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdministrativeUnitsTable::configure($table);
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
            'index' => ListAdministrativeUnits::route('/'),
            'create' => CreateAdministrativeUnit::route('/create'),
            'edit' => EditAdministrativeUnit::route('/{record}/edit'),
        ];
    }
}
