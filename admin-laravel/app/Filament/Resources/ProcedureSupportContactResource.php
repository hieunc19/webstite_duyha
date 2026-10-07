<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcedureSupportContactResource\Pages;
use App\Models\ProcedureSupportContact;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProcedureSupportContactResource extends Resource
{
    protected static ?string $model = ProcedureSupportContact::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-phone-arrow-up-right';

    protected static ?string $navigationLabel = 'Hỗ trợ thủ tục hành chính';

    protected static ?string $modelLabel = 'Liên hệ hỗ trợ';

    protected static ?string $pluralModelLabel = 'Hỗ trợ thủ tục hành chính';

    public static function getNavigationGroup(): ?string
    {
        return 'Dịch vụ Công & Cổng Thông tin';
    }

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Thông tin liên hệ hỗ trợ')
                ->description('Các liên hệ đang bật sẽ hiển thị tại trang Thủ tục hành chính. Có thể tải ảnh đại diện, hoặc để trống để hệ thống hiển thị biểu tượng mặc định.')
                ->columns(2)
                ->columnSpanFull()
                ->components([
                    TextInput::make('name')
                        ->label('Họ và tên cán bộ')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('role')
                        ->label('Chức danh / Ghi chú')
                        ->maxLength(255),

                    TextInput::make('phone')
                        ->label('Số điện thoại')
                        ->required()
                        ->tel()
                        ->maxLength(30),

                    TextInput::make('sort_order')
                        ->label('Thứ tự hiển thị')
                        ->numeric()
                        ->default(0),

                    Toggle::make('is_active')
                        ->label('Hiển thị trên website')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Cán bộ')->searchable(),
                TextColumn::make('phone')->label('Điện thoại')->copyable(),
                TextColumn::make('sort_order')->label('Thứ tự')->sortable(),
                IconColumn::make('is_active')->label('Hiển thị')->boolean(),
            ])
            ->defaultSort('sort_order')
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProcedureSupportContacts::route('/'),
            'create' => Pages\CreateProcedureSupportContact::route('/create'),
            'edit' => Pages\EditProcedureSupportContact::route('/{record}/edit'),
        ];
    }
}
