<?php

namespace App\Filament\Resources\Neighborhoods\Schemas;

use App\Models\Neighborhood;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class NeighborhoodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin Tổ dân phố Mới')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Tên Tổ dân phố')
                                ->required(),
                            Select::make('type')
                                ->label('Loại Tổ dân phố')
                                ->options([
                                    'new' => 'Sau sáp nhập (Mới)',
                                    'old' => 'Trước sáp nhập (Cũ)',
                                ])
                                ->required()
                                ->live(),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('households')
                                ->label('Số hộ gia đình')
                                ->required()
                                ->numeric(),
                            TextInput::make('people')
                                ->label('Số nhân khẩu')
                                ->required()
                                ->numeric(),
                            TextInput::make('area_ha')
                                ->label('Diện tích (ha)')
                                ->required()
                                ->numeric()
                                ->step(0.01),
                        ]),
                        Hidden::make('group_code'),
                        Hidden::make('status')
                            ->default('active'),
                    ]),

                Section::make('Thông tin Tổ dân phố Cũ')
                    ->columnSpanFull()
                    ->visible(fn ($get) => $get('type') === 'new')
                    ->schema([
                        Repeater::make('old_neighborhoods')
                            ->label('Danh sách Tổ dân phố Cũ thành phần')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('name')
                                        ->label('Tên Tổ dân phố Cũ')
                                        ->required(),
                                    Select::make('type')
                                        ->label('Loại Tổ dân phố')
                                        ->options([
                                            'old' => 'Trước sáp nhập (Cũ)',
                                        ])
                                        ->default('old')
                                        ->required(),
                                ]),
                                Grid::make(3)->schema([
                                    TextInput::make('households')
                                        ->label('Số hộ gia đình')
                                        ->numeric()
                                        ->required(),
                                    TextInput::make('people')
                                        ->label('Số nhân khẩu')
                                        ->numeric()
                                        ->required(),
                                    TextInput::make('area_ha')
                                        ->label('Diện tích (ha)')
                                        ->numeric()
                                        ->step(0.01)
                                        ->required(),
                                ]),
                            ])
                            ->itemLabel(fn (array $state): ?string => isset($state['name']) ? "TDP Cũ: {$state['name']}" : 'TDP Cũ')
                            ->addActionLabel('+ Thêm Tổ dân phố Cũ')
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),

                Section::make('Ban Cán sự & Cán bộ Phụ trách Địa bàn')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('bi_thu_name')
                                ->label('Bí thư Chi bộ'),
                            TextInput::make('bi_thu_phone')
                                ->label('SĐT Bí thư Chi bộ')
                                ->tel(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('to_truong_name')
                                ->label('Tổ trưởng TDP'),
                            TextInput::make('to_truong_phone')
                                ->label('SĐT Tổ trưởng TDP')
                                ->tel(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('cskv_name')
                                ->label('Cảnh sát khu vực'),
                            TextInput::make('cskv_phone')
                                ->label('SĐT Cảnh sát khu vực')
                                ->tel(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('mat_tan_name')
                                ->label('Trưởng ban Mặt trận'),
                            TextInput::make('mat_tan_phone')
                                ->label('SĐT Trưởng ban Mặt trận')
                                ->tel(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('nguoi_cao_tuoi')
                                ->label('Chi hội trưởng Người cao tuổi'),
                            TextInput::make('nguoi_cao_tuoi_phone')
                                ->label('SĐT Chi hội trưởng NCT')
                                ->tel(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('phu_nu')
                                ->label('Chi hội trưởng Hội Phụ nữ'),
                            TextInput::make('phu_nu_phone')
                                ->label('SĐT Chi hội trưởng Phụ nữ')
                                ->tel(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('nong_dan')
                                ->label('Chi hội trưởng Hội Nông dân'),
                            TextInput::make('nong_dan_phone')
                                ->label('SĐT Chi hội trưởng Nông dân')
                                ->tel(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('ccb')
                                ->label('Chi hội trưởng Hội CCB'),
                            TextInput::make('ccb_phone')
                                ->label('SĐT Chi hội trưởng CCB')
                                ->tel(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('doan_thanh_nien')
                                ->label('Bí thư Chi đoàn Thanh niên'),
                            TextInput::make('doan_thanh_nien_phone')
                                ->label('SĐT Bí thư Chi đoàn')
                                ->tel(),
                        ]),
                    ]),
            ]);
    }
}
