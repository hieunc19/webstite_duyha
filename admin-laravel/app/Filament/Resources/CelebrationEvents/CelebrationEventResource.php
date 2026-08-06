<?php

namespace App\Filament\Resources\CelebrationEvents;

use App\Filament\Resources\CelebrationEvents\Pages\CreateCelebrationEvent;
use App\Filament\Resources\CelebrationEvents\Pages\EditCelebrationEvent;
use App\Filament\Resources\CelebrationEvents\Pages\ListCelebrationEvents;
use App\Filament\Resources\CelebrationEvents\Schemas\CelebrationEventForm;
use App\Filament\Resources\CelebrationEvents\Tables\CelebrationEventsTable;
use App\Models\CelebrationEvent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CelebrationEventResource extends Resource
{
    protected static ?string $model = CelebrationEvent::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationLabel = 'Sự kiện kỷ niệm';

    protected static ?string $pluralLabel = 'Sự kiện kỷ niệm';

    protected static ?string $modelLabel = 'Sự kiện kỷ niệm';

    public static function getNavigationGroup(): ?string
    {
        return 'Quản lý địa bàn';
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CelebrationEventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CelebrationEventsTable::configure($table);
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
            'index' => ListCelebrationEvents::route('/'),
            'create' => CreateCelebrationEvent::route('/create'),
            'edit' => EditCelebrationEvent::route('/{record}/edit'),
        ];
    }
}
