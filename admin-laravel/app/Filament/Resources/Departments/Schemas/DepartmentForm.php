<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tên Đơn vị / Khối công tác')
                    ->placeholder('Ví dụ: Cảnh sát khu vực (CSKV)')
                    ->required(),
                TextInput::make('code')
                    ->label('Mã khối / Mã viết tắt')
                    ->placeholder('Ví dụ: cskv, cong_an, dang_uy...')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('color')
                    ->label('Màu nhãn hiển thị (Badge)')
                    ->options([
                        'danger' => 'Đỏ (Danger / Khối Công an/CSKV)',
                        'warning' => 'Cam (Warning / Khối UBND)',
                        'primary' => 'Xanh dương (Primary / Khối Đảng ủy)',
                        'success' => 'Xanh lá (Success / Khối Chính quyền)',
                        'info' => 'Xanh nhạt (Info / Khối Hành chính công)',
                        'gray' => 'Xám (Gray / Khác)',
                    ])
                    ->required()
                    ->default('danger'),
                TextInput::make('sort_order')
                    ->label('Thứ tự sắp xếp')
                    ->numeric()
                    ->default(0)
                    ->placeholder('Ví dụ: 1')
                    ->helperText('Số nhỏ hơn sẽ hiển thị trước trên trang chủ (Ví dụ: 1 hiển thị trên cùng).'),
                Textarea::make('description')
                    ->label('Mô tả / Chức năng nhiệm vụ')
                    ->placeholder('Nhập thông tin mô tả chi tiết chức năng nhiệm vụ của Đơn vị / Khối công tác...')
                    ->rows(3)
                    ->columnSpanFull()
                    ->nullable(),
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
