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
                Select::make('neighborhood_name')
                    ->label('Đơn vị / Cơ quan trực thuộc')
                    ->multiple()
                    ->options(function ($record) {
                        $options = [];

                        // Lấy danh sách trực tiếp từ bảng Đơn vị & Khối công tác (Department)
                        $departments = \App\Models\Department::where('status', 'active')
                            ->orderBy('sort_order')
                            ->get();

                        foreach ($departments as $dep) {
                            $options[$dep->name] = $dep->name;
                        }

                        // Giữ lại giá trị hiện tại của cán bộ nếu có
                        if ($record && !empty($record->neighborhood_name)) {
                            $currentValues = is_array($record->neighborhood_name) ? $record->neighborhood_name : [$record->neighborhood_name];
                            foreach ($currentValues as $val) {
                                if (!isset($options[$val])) {
                                    $options[$val] = $val;
                                }
                            }
                        }

                        return $options;
                    })
                    ->searchable()
                    ->preload()
                    ->helperText('Danh sách lấy tự động từ mục "Đơn vị & Khối công tác".')
                    ->nullable(),
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
