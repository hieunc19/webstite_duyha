<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcedureResource\Pages;
use App\Models\Procedure;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProcedureResource extends Resource
{
    protected static ?string $model = Procedure::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Thủ tục hành chính';

    protected static ?string $modelLabel = 'Thủ tục hành chính';

    protected static ?string $pluralModelLabel = 'Thủ tục hành chính';

    public static function getNavigationGroup(): ?string
    {
        return 'Dịch vụ Công & Cổng Thông tin';
    }

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin Thủ tục Hành chính')
                    ->description('Quản lý chi tiết quy trình, thành phần hồ sơ và biểu mẫu đính kèm cho công dân')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextInput::make('code')
                            ->label('Mã thủ tục')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('(Hệ thống tự động sinh mã khi lưu)'),

                        TextInput::make('title')
                            ->label('Tên thủ tục hành chính')
                            ->required()
                            ->maxLength(255),

                        Select::make('category')
                            ->label('Nhóm lĩnh vực')
                            ->options(fn () => \App\Models\ProcedureCategory::pluck('name', 'slug')->toArray())
                            ->required()
                            ->default('residence'),

                        TextInput::make('agency')
                            ->label('Cơ quan giải quyết')
                            ->default('Bộ phận Một cửa UBND Phường')
                            ->required()
                            ->maxLength(255),

                        Toggle::make('is_active')
                            ->label('Hiển thị trên website')
                            ->default(true),

                        Textarea::make('desc')
                            ->label('Trình tự thực hiện')
                            ->placeholder('Ghi rõ trình tự các bước thực hiện thủ tục hành chính cho công dân...')
                            ->rows(4)
                            ->columnSpanFull(),

                        Repeater::make('docs')
                            ->label('Danh sách Thành phần hồ sơ & Biểu mẫu đính kèm')
                            ->addActionLabel('Thêm thành phần hồ sơ')
                            ->defaultItems(1)
                            ->columnSpanFull()
                            ->schema([
                                Grid::make(12)->schema([
                                    Textarea::make('name')
                                        ->label('Thành phần hồ sơ')
                                        ->placeholder('Tên loại giấy tờ (VD: Tờ khai CT01)')
                                        ->required()
                                        ->rows(3)
                                        ->columnSpan(5),

                                    TextInput::make('quantity')
                                        ->label('Số lượng hồ sơ')
                                        ->placeholder('VD: 01 bản chính / 01 bản sao')
                                        ->default('01 bản chính')
                                        ->columnSpan(3),

                                    FileUpload::make('file')
                                        ->label('Biểu mẫu (PDF, Word, Excel)')
                                        ->directory('procedure-documents')
                                        ->disk('public')
                                        ->preserveFilenames()
                                        ->downloadable()
                                        ->openable()
                                        ->columnSpan(4),
                                ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Mã thủ tục')
                    ->badge()
                    ->searchable(),

                TextColumn::make('title')
                    ->label('Tên thủ tục hành chính')
                    ->searchable()
                    ->limit(35)
                    ->tooltip(fn (TextColumn $column): ?string => $column->getState()),

                TextColumn::make('category')
                    ->label('Lĩnh vực')
                    ->badge()
                    ->formatStateUsing(fn ($state) => \App\Models\ProcedureCategory::where('slug', $state)->value('name') ?? $state)
                    ->color(fn (string $state): string => \App\Models\ProcedureCategory::where('slug', $state)->value('color') ?? 'gray'),

                TextColumn::make('agency')
                    ->label('Cơ quan giải quyết'),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->date('d/m/Y')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Hiển thị')
                    ->boolean(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('category')
                    ->label('Lĩnh vực')
                    ->options([
                        'residence' => 'Cư trú & Hộ khẩu',
                        'civil' => 'Hộ tịch & Tư pháp',
                        'land' => 'Địa chính & Đất đai',
                        'vneid' => 'Định danh VNeID',
                        'social' => 'An sinh xã hội & Trợ cấp',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Trạng thái hiển thị'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProcedures::route('/'),
            'create' => Pages\CreateProcedure::route('/create'),
            'edit' => Pages\EditProcedure::route('/{record}/edit'),
        ];
    }
}
