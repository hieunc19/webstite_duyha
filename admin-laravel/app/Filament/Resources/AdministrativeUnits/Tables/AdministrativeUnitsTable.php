<?php

namespace App\Filament\Resources\AdministrativeUnits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AdministrativeUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('stt')
                    ->label('STT')
                    ->rowIndex(),
                TextColumn::make('name')
                    ->label('Tên đơn vị')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Loại hình')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                TextColumn::make('latitude')
                    ->label('Vĩ độ')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('longitude')
                    ->label('Kinh độ')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('link')
                    ->label('Google Maps')
                    ->icon('heroicon-o-link')
                    ->color('primary')
                    ->url(fn ($record) => $record->link ?? '#', true)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('boundary_data')
                    ->label('Dữ liệu địa giới')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return null;
                        $json = json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        $lines = explode("\n", $json);
                        if (count($lines) > 8) {
                            return implode("\n", array_slice($lines, 0, 8)) . "\n...";
                        }
                        return $json;
                    })
                    ->wrap()
                    ->fontFamily('mono')
                    ->size('xs')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
