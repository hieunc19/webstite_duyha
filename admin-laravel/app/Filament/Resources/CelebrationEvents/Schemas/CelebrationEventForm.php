<?php

namespace App\Filament\Resources\CelebrationEvents\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CelebrationEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tên sự kiện kỷ niệm / Vinh danh')
                    ->required()
                    ->placeholder('Ví dụ: Kỷ niệm ngày Thương binh - Liệt sĩ (27/07)'),
                TextInput::make('month')
                    ->label('Tháng diễn ra')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(12),
                TextInput::make('day')
                    ->label('Ngày diễn ra')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(31),
                Toggle::make('is_featured')
                    ->label('Hiển thị nổi bật trang chủ (Banner Tri ân)')
                    ->helperText('Bật tùy chọn này để hiển thị sự kiện này lên trang chủ kèm danh sách Gia đình có công được vinh danh.')
                    ->default(false),
                Textarea::make('description')
                    ->label('Thông điệp tri ân / Lời giới thiệu sự kiện')
                    ->placeholder('Nhập lời tri ân sâu sắc đến các anh hùng liệt sĩ, thương bệnh binh và người có công...')
                    ->rows(3)
                    ->columnSpanFull()
                    ->default(null),
                Select::make('status')
                    ->label('Trạng thái sự kiện')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Tạm ngưng',
                    ])
                    ->required()
                    ->default('active'),
            ]);
    }
}
