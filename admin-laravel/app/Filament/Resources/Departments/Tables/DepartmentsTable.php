<?php

namespace App\Filament\Resources\Departments\Tables;

use App\Models\Official;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DepartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên Đơn vị / Khối công tác')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->label('Mã khối')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('color')
                    ->label('Màu nhãn')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'danger', 'red' => 'danger',
                        'warning', 'amber' => 'warning',
                        'primary', 'blue' => 'primary',
                        'success', 'emerald' => 'success',
                        'info', 'sky' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('officials_count')
                    ->label('Số Cán bộ')
                    ->getStateUsing(fn ($record) => Official::where('department', $record->code)->count() . ' Cán bộ')
                    ->badge()
                    ->color('info'),
                TextColumn::make('sort_order')
                    ->label('Thứ tự')
                    ->sortable(),
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
            ->defaultSort('sort_order', 'asc')
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
