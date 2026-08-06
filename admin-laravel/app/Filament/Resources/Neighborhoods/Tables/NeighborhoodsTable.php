<?php

namespace App\Filament\Resources\Neighborhoods\Tables;

use App\Models\Neighborhood;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NeighborhoodsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên Tổ dân phố')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Phân loại')
                    ->formatStateUsing(fn (string $state): string => $state === 'old' ? 'Cũ (Trước sáp nhập)' : 'Mới (Sau sáp nhập)')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'old' ? 'gray' : 'success')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('group_code')
                    ->label('Mã nhóm')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('relation')
                    ->label('Liên kết sáp nhập')
                    ->getStateUsing(function (Neighborhood $record): string {
                        if ($record->type === 'new') {
                            $olds = Neighborhood::where('type', 'old')
                                ->where('group_code', $record->group_code)
                                ->pluck('name')
                                ->map(function ($name) {
                                    $clean = preg_replace('/^(TDP|Tổ dân phố)\s+/ui', '', trim($name));
                                    return "TDP {$clean}";
                                })
                                ->join(', ');
                            return $olds ? "Gộp từ: {$olds}" : "Chưa có TDP cũ";
                        } else {
                            $new = Neighborhood::where('type', 'new')
                                ->where('group_code', $record->group_code)
                                ->first();
                            if (!$new) return "Chưa sáp nhập";
                            $clean = preg_replace('/^(TDP|Tổ dân phố)\s+/ui', '', trim($new->name));
                            return "Gộp vào: TDP {$clean}";
                        }
                    })
                    ->badge()
                    ->color(fn (Neighborhood $record): string => $record->type === 'new' ? 'success' : 'warning'),
                TextColumn::make('leader_name')
                    ->label('Cán bộ CSKV phụ trách')
                    ->placeholder('Chưa phân công')
                    ->searchable(),
                TextColumn::make('leader_phone')
                    ->label('SĐT CSKV')
                    ->placeholder('Chưa phân công')
                    ->searchable(),
                TextColumn::make('households')
                    ->label('Số hộ GD')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('people')
                    ->label('Nhân khẩu')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('area_ha')
                    ->label('Diện tích (ha)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'danger')
                    ->formatStateUsing(fn (string $state): string => $state === 'active' ? 'Hoạt động' : 'Tạm ngưng')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Phân loại')
                    ->options([
                        'old' => 'Trước sáp nhập (Cũ)',
                        'new' => 'Sau sáp nhập (Mới)',
                    ]),
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

