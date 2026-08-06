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
                    ->label('Khối / Lĩnh vực công tác')
                    ->options(function () {
                        $deps = \App\Models\Department::where('status', 'active')->orderBy('sort_order')->pluck('name', 'code')->toArray();
                        return !empty($deps) ? $deps : [
                            'cskv' => 'Cảnh sát khu vực (CSKV)',
                            'cong_an' => 'Công an Phường Duy Hà',
                            'dang_uy' => 'Đảng ủy Phường',
                            'chinh_quyen' => 'UBND / Chính quyền',
                            'ttpvhcc' => 'Hành chính công',
                        ];
                    })
                    ->required()
                    ->default('cskv'),
                Select::make('neighborhood_name')
                    ->label('Địa bàn / TDP Phụ trách')
                    ->multiple()
                    ->options(function ($record) {
                        $options = [];
                        $currentOfficerName = $record?->name;

                        // Lấy danh sách các TDP mới (Sau sáp nhập)
                        $newNeighborhoods = Neighborhood::where('type', 'new')->orderBy('id')->get();

                        foreach ($newNeighborhoods as $n) {
                            $cleanName = str_replace(['TDP ', 'Tổ dân phố '], '', $n->name);
                            $value = "TDP {$cleanName}";

                            // Kiểm tra nếu TDP đã có Cán bộ CSKV khác phụ trách
                            $isAssignedToOther = !empty($n->leader_name) && ($currentOfficerName === null || $n->leader_name !== $currentOfficerName);

                            // Nếu đã thuộc về CSKV khác -> ẨN hoàn toàn khỏi danh sách lựa chọn!
                            if ($isAssignedToOther) {
                                continue;
                            }

                            $options[$value] = $value;
                        }

                        // Thêm các Đơn vị công tác chính
                        $units = [
                            'Công an Phường Duy Hà' => 'Công an Phường Duy Hà',
                            'UBND Phường Duy Hà' => 'UBND Phường Duy Hà',
                            'Đảng ủy Phường Duy Hà' => 'Đảng ủy Phường Duy Hà',
                            'Trung tâm Phục vụ Hành chính công' => 'Trung tâm Phục vụ Hành chính công',
                        ];
                        foreach ($units as $val => $lbl) {
                            if (!isset($options[$val])) {
                                $options[$val] = $lbl;
                            }
                        }

                        return $options;
                    })
                    ->searchable()
                    ->helperText('Chỉ hiển thị các TDP chưa có người phụ trách (và TDP đang do Cán bộ này phụ trách). TDP đã có CSKV khác đảm nhiệm sẽ bị ẩn.')
                    ->nullable(),
                TextInput::make('avatar_color')
                    ->label('Màu nhãn đại diện')
                    ->default('#B91C1C')
                    ->nullable(),
                TextInput::make('avatar')
                    ->label('Đường dẫn ảnh đại diện (URL)')
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
