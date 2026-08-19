<?php

namespace App\Filament\Resources\MeritoriousFamilies\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MeritoriousFamilyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tên đợt danh sách chính sách')
                    ->required()
                    ->placeholder('Ví dụ: Đợt trao quà Tết Nguyên Đán 2026, Danh sách tri ân ngày 27/7...')
                    ->columnSpanFull(),
                
                FileUpload::make('file_path')
                    ->label('Tệp Excel danh sách (.xlsx, .xls, .csv, .pdf)')
                    ->disk('public')
                    ->directory('meritorious_files')
                    ->acceptedFileTypes([
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-excel',
                        'text/csv',
                        'application/csv',
                        'application/pdf',
                    ])
                    ->maxSize(51200)
                    ->openable()
                    ->downloadable()
                    ->storeFileNamesIn('file_name')
                    ->helperText('Tải lên file Excel danh sách chi tiết các gia đình chính sách trong đợt này.')
                    ->required()
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('Ghi chú / Mô tả đợt')
                    ->placeholder('Nhập thông tin mô tả chi tiết về đối tượng, nội dung quà tặng hoặc chế độ trong đợt này...')
                    ->rows(3)
                    ->columnSpanFull()
                    ->nullable(),

                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động / Đang áp dụng',
                        'inactive' => 'Tạm ngưng',
                    ])
                    ->required()
                    ->default('active')
                    ->columnSpanFull(),
            ]);
    }
}
