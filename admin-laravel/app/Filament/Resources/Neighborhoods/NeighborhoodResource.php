<?php

namespace App\Filament\Resources\Neighborhoods;

use App\Filament\Resources\Neighborhoods\Pages\CreateNeighborhood;
use App\Filament\Resources\Neighborhoods\Pages\EditNeighborhood;
use App\Filament\Resources\Neighborhoods\Pages\ListNeighborhoods;
use App\Filament\Resources\Neighborhoods\Schemas\NeighborhoodForm;
use App\Filament\Resources\Neighborhoods\Tables\NeighborhoodsTable;
use App\Models\Neighborhood;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NeighborhoodResource extends Resource
{
    protected static ?string $model = Neighborhood::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'Tổ dân phố';

    protected static ?string $pluralLabel = 'Tổ dân phố';

    protected static ?string $modelLabel = 'Tổ dân phố';

    public static function getNavigationGroup(): ?string
    {
        return 'Quản lý địa bàn';
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', 'new');
    }

    public static function form(Schema $schema): Schema
    {
        return NeighborhoodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NeighborhoodsTable::configure($table);
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
            'index' => ListNeighborhoods::route('/'),
            'create' => CreateNeighborhood::route('/create'),
            'edit' => EditNeighborhood::route('/{record}/edit'),
        ];
    }
}
