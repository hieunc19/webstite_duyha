<?php

namespace App\Filament\Resources\MeritoriousFamilies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MeritoriousFamilyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tên gia đình có công')
                    ->required()
                    ->placeholder('Ví dụ: Gia đình Liệt sĩ Nguyễn Văn Đạt'),
                Select::make('type')
                    ->label('Phân loại diện chính sách')
                    ->options([
                        'Gia đình Liệt sĩ' => 'Gia đình Liệt sĩ',
                        'Thương binh 1/4' => 'Thương binh 1/4',
                        'Thương binh 2/4' => 'Thương binh 2/4',
                        'Thương binh 3/4' => 'Thương binh 3/4',
                        'Thương binh 4/4' => 'Thương binh 4/4',
                        'Bệnh binh 1/4' => 'Bệnh binh 1/4',
                        'Bệnh binh 2/4' => 'Bệnh binh 2/4',
                        'Lão thành Cách mạng' => 'Lão thành Cách mạng',
                        'Cựu chiến binh' => 'Cựu chiến binh',
                        'Gia đình có công với Cách mạng' => 'Gia đình có công với Cách mạng',
                    ])
                    ->required(),
                Select::make('celebration_event_id')
                    ->relationship('celebrationEvent', 'name')
                    ->label('Thuộc sự kiện kỷ niệm')
                    ->required(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Tạm ngưng',
                    ])
                    ->required()
                    ->default('active'),
            ]);
    }
}
