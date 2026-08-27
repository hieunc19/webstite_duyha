<?php

namespace App\Filament\Resources\Officials\Schemas;

use App\Models\Neighborhood;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OfficialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Cấp bậc & Họ tên Cán bộ')
                    ->placeholder('Ví dụ: Đại úy Trần Hữu Tiến')
                    ->required(),
                TextInput::make('role')
                    ->label('Chức danh / Nhiệm vụ')
                    ->placeholder('Ví dụ: Cán bộ CSKV Phụ trách TDP Duy Minh')
                    ->required(),
                TextInput::make('phone')
                    ->label('Số điện thoại liên hệ')
                    ->placeholder('Ví dụ: 0986.361.395')
                    ->required()
                    ->tel(),
                Select::make('department')
                    ->label('Phòng ban / Ban ngành trực thuộc')
                    ->options(fn () => \App\Models\Department::where('status', 'active')->orderBy('sort_order')->pluck('name', 'code')->toArray())
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Chọn khối ban ngành / phòng ban công tác (Đảng ủy, UBND, Hành chính công, v.v.).'),
                TextInput::make('avatar_color')
                    ->label('Màu nhãn đại diện')
                    ->default('#B91C1C')
                    ->nullable(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Đang công tác',
                        'inactive' => 'Tạm ngưng',
                    ])
                    ->required()
                    ->default('active'),
            ]);
    }
}
