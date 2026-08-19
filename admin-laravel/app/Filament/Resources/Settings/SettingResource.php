<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\Pages\ListSettings;
use App\Filament\Resources\Settings\Pages\EditSetting;
use App\Models\Setting;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Cấu hình Trang chủ';

    protected static ?string $pluralLabel = 'Cấu hình Trang chủ';

    protected static ?string $modelLabel = 'Cấu hình';

    public static function getNavigationGroup(): ?string
    {
        return 'Cấu hình Hệ thống';
    }

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Tên thẻ số liệu')
                ->disabled(),
            TextInput::make('value')
                ->label('Giá trị số liệu')
                ->required(),
            TextInput::make('label')
                ->label('Nhãn chữ hiển thị')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Vị trí')
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                TextColumn::make('name')
                    ->label('Thẻ thống kê')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('value')
                    ->label('Giá trị số liệu')
                    ->searchable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('label')
                    ->label('Nhãn chữ hiển thị')
                    ->searchable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('updated_at')
                    ->label('Cập nhật lần cuối')
                    ->dateTime(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->recordActions([
                EditAction::make()
                    ->label('Chỉnh sửa'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSettings::route('/'),
            'edit' => EditSetting::route('/{record}/edit'),
        ];
    }
}
