<?php

namespace App\Filament\Resources;

use App\Models\WasteSchedule;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WasteScheduleResource extends Resource
{
    protected static ?string $model = WasteSchedule::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-trash';

    protected static ?string $navigationLabel = 'Lịch thu gom rác';

    protected static ?string $modelLabel = 'Lịch thu gom rác';

    protected static ?string $pluralModelLabel = 'Lịch thu gom rác sinh hoạt';

    public static function getNavigationGroup(): ?string
    {
        return 'Quản lý Địa bàn & Dân cư';
    }

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lịch thu gom rác theo địa bàn')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextInput::make('tdp_name')
                            ->label('Tên Tổ dân phố')
                            ->placeholder('Ví dụ: TDP Ngọc Tú')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->dehydrateStateUsing(fn (?string $state): string => trim((string) $state))
                            ->columnSpanFull(),

                        TextInput::make('morning_shift')
                            ->label('Khung giờ thu gom rác')
                            ->placeholder('Ví dụ: 05h30 - 07h00 hoặc 17h00 - 18h30')
                            ->suffixIcon('heroicon-m-clock')
                            ->live(onBlur: true)
                            ->required(),

                        ViewField::make('collection_dates')
                            ->label('Ngày thu gom rác')
                            ->view('filament.forms.components.waste-schedule-calendar')
                            ->default([])
                            ->required()
                            ->live()
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Hiển thị trên website')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('tdp_name')
                    ->label('Tổ dân phố')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('morning_shift')
                    ->label('Khung giờ thu gom')
                    ->placeholder('—')
                    ->badge()
                    ->color('success'),

                IconColumn::make('is_active')
                    ->label('Hiển thị')
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('id', 'asc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => WasteScheduleResource\Pages\ListWasteSchedules::route('/'),
            'create' => WasteScheduleResource\Pages\CreateWasteSchedule::route('/create'),
            'edit' => WasteScheduleResource\Pages\EditWasteSchedule::route('/{record}/edit'),
        ];
    }
}
