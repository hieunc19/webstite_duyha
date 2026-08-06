<?php

namespace App\Filament\Resources\TdpOfficials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TdpOfficialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tdp_name')
                    ->label('Tên Tổ dân phố')
                    ->required(),
                TextInput::make('bi_thu_name')
                    ->label('Bí thư Chi bộ'),
                TextInput::make('bi_thu_phone')
                    ->label('SĐT Bí thư Chi bộ')
                    ->tel(),
                TextInput::make('to_truong_name')
                    ->label('Tổ trưởng TDP'),
                TextInput::make('to_truong_phone')
                    ->label('SĐT Tổ trưởng TDP')
                    ->tel(),
                TextInput::make('cskv_name')
                    ->label('Cảnh sát khu vực'),
                TextInput::make('cskv_phone')
                    ->label('SĐT Cảnh sát khu vực')
                    ->tel(),
                TextInput::make('mat_tan_name')
                    ->label('Trưởng ban Mặt trận'),
                TextInput::make('mat_tan_phone')
                    ->label('SĐT Trưởng ban Mặt trận')
                    ->tel(),
                TextInput::make('nguoi_cao_tuoi')
                    ->label('Chi hội trưởng Người cao tuổi'),
                TextInput::make('nguoi_cao_tuoi_phone')
                    ->label('SĐT Người cao tuổi')
                    ->tel(),
                TextInput::make('phu_nu')
                    ->label('Chi hội trưởng Hội Phụ nữ'),
                TextInput::make('phu_nu_phone')
                    ->label('SĐT Hội Phụ nữ')
                    ->tel(),
                TextInput::make('nong_dan')
                    ->label('Chi hội trưởng Hội Nông dân'),
                TextInput::make('nong_dan_phone')
                    ->label('SĐT Hội Nông dân')
                    ->tel(),
                TextInput::make('ccb')
                    ->label('Chi hội trưởng Hội CCB'),
                TextInput::make('ccb_phone')
                    ->label('SĐT Hội CCB')
                    ->tel(),
                TextInput::make('doan_thanh_nien')
                    ->label('Bí thư Chi đoàn Thanh niên'),
                TextInput::make('doan_thanh_nien_phone')
                    ->label('SĐT Chi đoàn Thanh niên')
                    ->tel(),
            ]);
    }
}
