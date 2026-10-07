<?php

namespace App\Filament\Resources;

use App\Models\FormDocument;
use App\Models\ProcedureCategory;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FormDocumentResource extends Resource
{
    protected static ?string $model = FormDocument::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $navigationLabel = 'Kho Biểu mẫu thủ tục';

    protected static ?string $modelLabel = 'Biểu mẫu thủ tục';

    protected static ?string $pluralModelLabel = 'Kho Biểu mẫu thủ tục';

    public static function getNavigationGroup(): ?string
    {
        return 'Dịch vụ Công & Cổng Thông tin';
    }

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin Biểu mẫu thủ tục hành chính')
                    ->description('Cấu hình đường dẫn chính thức trên Cổng DVC Quốc gia. Tệp cũ chỉ dùng để lưu trữ nội bộ, không hiển thị công khai.')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextInput::make('title')
                            ->label('Tên biểu mẫu thủ tục')
                            ->placeholder('Ví dụ: Tờ khai đăng ký khai sinh')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Select::make('category')
                            ->label('Lĩnh vực thủ tục')
                            ->options(function () {
                                return ProcedureCategory::pluck('name', 'slug')->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('agency')
                            ->label('Cơ quan / Bộ phận tiếp nhận')
                            ->placeholder('Ví dụ: Bộ phận Tư pháp - Hộ tịch')
                            ->default('Bộ phận Tư pháp - Hộ tịch')
                            ->required(),

                        TextInput::make('public_service_url')
                            ->label('Đường dẫn chính thức trên Cổng DVC Quốc gia')
                            ->helperText('Dán đường dẫn chi tiết tại dichvucong.gov.vn. Nếu chưa có, giữ đường dẫn trang chủ để công dân tra cứu.')
                            ->default('https://dichvucong.gov.vn/')
                            ->required()
                            ->url()
                            ->rule('regex:/^https:\/\/([a-z0-9-]+\.)?dichvucong\.gov\.vn(?:\/|$)/i')
                            ->validationMessages([
                                'regex' => 'Đường dẫn phải thuộc tên miền dichvucong.gov.vn.',
                            ])
                            ->columnSpanFull(),

                        FileUpload::make('file_path')
                            ->label('Tệp lưu trữ nội bộ (không hiển thị công khai)')
                            ->directory('form-documents')
                            ->disk('public')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),

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
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Tên biểu mẫu thủ tục')
                    ->searchable(),

                TextColumn::make('category_text')
                    ->label('Lĩnh vực')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                TextColumn::make('agency')
                    ->label('Cơ quan tiếp nhận')
                    ->searchable(),

                TextColumn::make('file_path')
                    ->label('Tệp nội bộ')
                    ->formatStateUsing(fn ($state) => $state ? '📄 ' . basename($state) : '—')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                TextColumn::make('public_service_url')
                    ->label('Link DVCQG')
                    ->limit(35)
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('Hiển thị')
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('id', 'asc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => FormDocumentResource\Pages\ListFormDocuments::route('/'),
            'create' => FormDocumentResource\Pages\CreateFormDocument::route('/create'),
            'edit' => FormDocumentResource\Pages\EditFormDocument::route('/{record}/edit'),
        ];
    }
}
