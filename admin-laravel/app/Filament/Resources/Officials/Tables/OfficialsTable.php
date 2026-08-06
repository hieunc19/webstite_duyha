<?php

namespace App\Filament\Resources\Officials\Tables;

use App\Models\Department;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OfficialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Họ tên & Cấp bậc')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label('Chức danh / Nhiệm vụ')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->searchable(),
                TextColumn::make('department')
                    ->label('Đơn vị / Khối')
                    ->badge()
                    ->color(function (string $state): string {
                        $dep = Department::where('code', $state)->first();
                        return $dep ? $dep->color : match ($state) {
                            'cskv' => 'danger',
                            'cong_an' => 'warning',
                            'dang_uy' => 'primary',
                            'chinh_quyen' => 'success',
                            'ttpvhcc' => 'info',
                            default => 'gray',
                        };
                    })
                    ->formatStateUsing(function (string $state): string {
                        $dep = Department::where('code', $state)->first();
                        return $dep ? $dep->name : match ($state) {
                            'cskv' => 'Cảnh sát khu vực (CSKV)',
                            'cong_an' => 'Công an Phường',
                            'dang_uy' => 'Đảng ủy Phường',
                            'chinh_quyen' => 'UBND Phường',
                            'ttpvhcc' => 'Hành chính công',
                            default => $state,
                        };
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('neighborhood_name')
                    ->label('Địa bàn phụ trách')
                    ->placeholder('Chưa phân công')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'danger')
                    ->formatStateUsing(fn (string $state): string => $state === 'active' ? 'Đang công tác' : 'Tạm ngưng')
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('department')
                    ->label('Khối / Lĩnh vực')
                    ->options(fn () => Department::where('status', 'active')->orderBy('sort_order')->pluck('name', 'code')->toArray()),
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Đang công tác',
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
