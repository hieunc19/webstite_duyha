<?php

namespace App\Filament\Resources\MeritoriousFamilies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MeritoriousFamiliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Hộ Gia đình có công')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Diện chính sách')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        str_contains($state, 'Mẹ Việt Nam Anh hùng') => 'danger',
                        str_contains($state, 'Liệt sĩ') => 'warning',
                        str_contains($state, 'Thương binh') => 'primary',
                        default => 'success',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('celebrationEvent.name')
                    ->label('Sự kiện vinh danh')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'danger')
                    ->formatStateUsing(fn (string $state): string => $state === 'active' ? 'Đang vinh danh' : 'Tạm ngưng')
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('celebration_event_id')
                    ->relationship('celebrationEvent', 'name')
                    ->label('Sự kiện vinh danh'),
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Đang vinh danh',
                        'inactive' => 'Tạm ngưng',
                    ]),
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
