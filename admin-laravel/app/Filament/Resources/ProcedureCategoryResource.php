<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcedureCategoryResource\Pages;
use App\Models\ProcedureCategory;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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

class ProcedureCategoryResource extends Resource
{
    protected static ?string $model = ProcedureCategory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Danh mục Lĩnh vực';

    protected static ?string $modelLabel = 'Lĩnh vực';

    protected static ?string $pluralModelLabel = 'Danh mục Lĩnh vực';

    public static function getNavigationGroup(): ?string
    {
        return 'Dịch vụ Công & Cổng Thông tin';
    }

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin Lĩnh vực Dùng chung')
                    ->description('Lĩnh vực dùng chung cho Thủ tục hành chính, Video hướng dẫn và Chính sách quy định')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->label('Tên Lĩnh vực')
                            ->placeholder('Ví dụ: Cư trú & Định danh điện tử')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Select::make('color')
                            ->label('Màu sắc nhãn (Badge)')
                            ->options([
                                'info'    => 'Xanh dương (Info)',
                                'success' => 'Xanh lá (Success)',
                                'warning' => 'Cam (Warning)',
                                'danger'  => 'Đỏ (Danger)',
                                'primary' => 'Tím/Xanh đậm (Primary)',
                                'gray'    => 'Xám (Gray)',
                            ])
                            ->default('info')
                            ->required()
                            ->columnSpanFull(),
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

                TextColumn::make('name')
                    ->label('Tên Lĩnh vực')
                    ->searchable()
                    ->limit(35)
                    ->tooltip(fn (TextColumn $column): ?string => $column->getState()),

                TextColumn::make('color')
                    ->label('Màu nhãn')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'info'    => 'info',
                        'success' => 'success',
                        'warning' => 'warning',
                        'danger'  => 'danger',
                        'primary' => 'primary',
                        default   => 'gray',
                    }),

                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
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
            'index' => Pages\ListProcedureCategories::route('/'),
            'create' => Pages\CreateProcedureCategory::route('/create'),
            'edit' => Pages\EditProcedureCategory::route('/{record}/edit'),
        ];
    }
}
