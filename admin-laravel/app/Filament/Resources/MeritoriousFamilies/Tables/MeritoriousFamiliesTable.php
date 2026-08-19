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
                    ->label('Tên đợt danh sách chính sách')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('file_path')
                    ->label('Tệp đính kèm')
                    ->formatStateUsing(function ($record) {
                        return $record->file_name ?: 'Tệp Excel (.xlsx)';
                    })
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn ($record) => $record->file_url, true),
                TextColumn::make('description')
                    ->label('Ghi chú / Nội dung đợt')
                    ->limit(50)
                    ->placeholder('Không có mô tả')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'danger')
                    ->formatStateUsing(fn (string $state): string => $state === 'active' ? 'Hoạt động' : 'Tạm ngưng')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Ngày tải lên')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
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
