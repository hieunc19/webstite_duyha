<?php

namespace App\Filament\Resources\TdpOfficials\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TdpOfficialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tdp_name')
                    ->label('Tên Tổ dân phố')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bi_thu_name')
                    ->label('Bí thư Chi bộ')
                    ->searchable(),
                TextColumn::make('bi_thu_phone')
                    ->label('SĐT Bí thư')
                    ->searchable(),
                TextColumn::make('to_truong_name')
                    ->label('Tổ trưởng TDP')
                    ->searchable(),
                TextColumn::make('to_truong_phone')
                    ->label('SĐT Tổ trưởng')
                    ->searchable(),
                TextColumn::make('cskv_name')
                    ->label('Cảnh sát khu vực')
                    ->searchable(),
                TextColumn::make('cskv_phone')
                    ->label('SĐT CSKV')
                    ->searchable(),
                TextColumn::make('mat_tan_name')
                    ->label('Trưởng ban Mặt trận')
                    ->searchable(),
                TextColumn::make('nguoi_cao_tuoi')
                    ->label('Người cao tuổi')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phu_nu')
                    ->label('Hội Phụ nữ')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nong_dan')
                    ->label('Hội Nông dân')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ccb')
                    ->label('Hội CCB')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('doan_thanh_nien')
                    ->label('Đoàn thanh niên')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
