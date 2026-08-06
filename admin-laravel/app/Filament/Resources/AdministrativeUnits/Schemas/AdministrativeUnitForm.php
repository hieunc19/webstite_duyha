<?php

namespace App\Filament\Resources\AdministrativeUnits\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Schema;

class AdministrativeUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tên đơn vị')
                    ->required(),
                TextInput::make('type')
                    ->label('Loại hình')
                    ->placeholder('Xã/Phường/Thị trấn')
                    ->default(null),
                TextInput::make('latitude')
                    ->label('Vĩ độ')
                    ->numeric()
                    ->default(null),
                TextInput::make('longitude')
                    ->label('Kinh độ')
                    ->numeric()
                    ->default(null),
                TextInput::make('link')
                    ->label('Link Google Maps')
                    ->url()
                    ->default(null),
                CodeEditor::make('boundary_data')
                    ->label('Dữ liệu địa giới (GeoJSON)')
                    ->helperText('Chứa dữ liệu MultiPolygon cho việc vẽ ranh giới trên bản đồ.')
                    ->language(Language::Json)
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)
                    ->extraAttributes(['style' => 'max-height: 300px; overflow: auto;'])
                    ->default(null)
                    ->columnSpanFull()
                    ->live(),
                ViewField::make('map_preview')
                    ->label('Xem trước địa giới')
                    ->view('filament.forms.components.map-preview')
                    ->columnSpanFull(),
            ]);
    }
}
