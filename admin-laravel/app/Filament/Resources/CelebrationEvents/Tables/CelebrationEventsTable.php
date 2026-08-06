<?php

namespace App\Filament\Resources\CelebrationEvents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CelebrationEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên sự kiện kỷ niệm')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('event_date')
                    ->label('Thời gian')
                    ->getStateUsing(fn ($record): string => sprintf('Ngày %02d/%02d hàng năm', $record->day, $record->month))
                    ->badge()
                    ->color('primary')
                    ->sortable(query: fn ($query, $direction) => $query->orderBy('month', $direction)->orderBy('day', $direction)),
                IconColumn::make('is_featured')
                    ->label('Nổi bật trang chủ')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-minus-circle')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->sortable(),
                TextColumn::make('families_count')
                    ->label('Gia đình vinh danh')
                    ->getStateUsing(fn ($record): string => $record->meritoriousFamilies()->count() . ' Hộ gia đình')
                    ->badge()
                    ->color('success'),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'danger')
                    ->formatStateUsing(fn (string $state): string => $state === 'active' ? 'Hoạt động' : 'Tạm ngưng')
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
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
