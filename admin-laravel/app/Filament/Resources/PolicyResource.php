<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PolicyResource\Pages;
use App\Models\Policy;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PolicyResource extends Resource
{
    protected static ?string $model = Policy::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Chính sách & Quy định';

    protected static ?string $modelLabel = 'Chính sách / Quy định';

    protected static ?string $pluralModelLabel = 'Chính sách & Quy định';

    public static function getNavigationGroup(): ?string
    {
        return 'Dịch vụ Công & Cổng Thông tin';
    }

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin Văn bản Chính sách & Quy định')
                    ->description('Quản lý thông tin văn bản pháp luật, nghị định, quyết định, quy định công khai')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextInput::make('title')
                            ->label('Tên chính sách / Tên văn bản quy định')
                            ->placeholder('Ví dụ: Nghị định quy định chi tiết một số điều của Luật Cư trú năm 2020...')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('code')
                            ->label('Số hiệu văn bản')
                            ->placeholder('Ví dụ: Nghị định 144/2021/NĐ-CP, Quyết định 06/QĐ-TTg...'),

                        Select::make('category')
                            ->label('Lĩnh vực')
                            ->options(fn () => \App\Models\ProcedureCategory::pluck('name', 'slug')->toArray())
                            ->required()
                            ->default('residence'),

                        TextInput::make('agency')
                            ->label('Cơ quan ban hành')
                            ->placeholder('Ví dụ: Chính phủ, Quốc hội, HĐND Tỉnh...'),

                        Select::make('status')
                            ->label('Trạng thái hiệu lực')
                            ->options([
                                'Đang có hiệu lực' => 'Đang có hiệu lực',
                                'Chưa có hiệu lực' => 'Chưa có hiệu lực',
                                'Hết hiệu lực'     => 'Hết hiệu lực',
                            ])
                            ->default('Đang có hiệu lực')
                            ->required(),

                        FileUpload::make('download_url')
                            ->label('Tải lên tệp văn bản PDF (Chỉ chấp nhận tệp .pdf)')
                            ->directory('policy-documents')
                            ->disk('public')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->acceptedFileTypes([
                                'application/pdf',
                            ])
                            ->maxSize(51200)
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Hiển thị trên Cổng thông tin')
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

                TextColumn::make('code')
                    ->label('Số hiệu')
                    ->searchable()
                    ->limit(25)
                    ->tooltip(fn (TextColumn $column): ?string => $column->getState()),

                TextColumn::make('title')
                    ->label('Tên chính sách / Văn bản')
                    ->searchable()
                    ->limit(35)
                    ->tooltip(fn (TextColumn $column): ?string => $column->getState()),

                TextColumn::make('category')
                    ->label('Lĩnh vực')
                    ->badge()
                    ->formatStateUsing(fn ($state) => \App\Models\ProcedureCategory::where('slug', $state)->value('name') ?? $state)
                    ->color(fn (string $state): string => \App\Models\ProcedureCategory::where('slug', $state)->value('color') ?? 'gray'),

                TextColumn::make('agency')
                    ->label('Cơ quan ban hành'),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Đang có hiệu lực' => 'success',
                        'Chưa có hiệu lực' => 'warning',
                        'Hết hiệu lực'     => 'danger',
                        default            => 'gray',
                    }),

                IconColumn::make('is_active')
                    ->label('Hiển thị')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Lọc theo lĩnh vực')
                    ->options([
                        'residence' => 'Cư trú & Định danh',
                        'social'    => 'An sinh xã hội',
                        'land'      => 'Đất đai & Xây dựng',
                        'civil'     => 'Hộ tịch & Chứng thực',
                        'other'     => 'Lĩnh vực khác',
                    ]),
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
            'index' => Pages\ListPolicies::route('/'),
            'create' => Pages\CreatePolicy::route('/create'),
            'edit' => Pages\EditPolicy::route('/{record}/edit'),
        ];
    }
}
