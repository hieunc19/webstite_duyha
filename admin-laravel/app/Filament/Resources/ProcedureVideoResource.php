<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcedureVideoResource\Pages;
use App\Models\ProcedureVideo;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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

class ProcedureVideoResource extends Resource
{
    protected static ?string $model = ProcedureVideo::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationLabel = 'Video hướng dẫn thủ tục';

    protected static ?string $modelLabel = 'Video hướng dẫn thủ tục';

    protected static ?string $pluralModelLabel = 'Video hướng dẫn thủ tục';

    public static function getNavigationGroup(): ?string
    {
        return 'Dịch vụ Công & Cổng Thông tin';
    }

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin Video Hướng dẫn Thủ tục')
                    ->description('Quản lý thông tin video nhúng hướng dẫn thực hiện các thủ tục hành chính')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextInput::make('title')
                            ->label('Tên thủ tục / Video hướng dẫn')
                            ->placeholder('Ví dụ: Video hướng dẫn Nộp hồ sơ Đăng ký tạm trú trực tuyến...')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Select::make('category')
                            ->label('Nhóm lĩnh vực')
                            ->options(fn () => \App\Models\ProcedureCategory::pluck('name', 'slug')->toArray())
                            ->required()
                            ->default('residence')
                            ->columnSpanFull(),

                        Textarea::make('video_url')
                            ->label('Đường dẫn nhúng (Embed URL / iframe link)')
                            ->placeholder('Ví dụ: https://www.youtube.com/embed/5qap5aO4i9A')
                            ->required()
                            ->rows(3)
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

                TextColumn::make('title')
                    ->label('Tên thủ tục / Video hướng dẫn')
                    ->searchable()
                    ->limit(35)
                    ->tooltip(fn (TextColumn $column): ?string => $column->getState()),

                TextColumn::make('category')
                    ->label('Lĩnh vực')
                    ->badge()
                    ->formatStateUsing(fn ($state) => \App\Models\ProcedureCategory::where('slug', $state)->value('name') ?? $state)
                    ->color(fn (string $state): string => \App\Models\ProcedureCategory::where('slug', $state)->value('color') ?? 'gray'),

                TextColumn::make('video_url')
                    ->label('Đường dẫn nhúng')
                    ->limit(25)
                    ->copyable()
                    ->tooltip(fn ($record) => $record->video_url),

                IconColumn::make('is_active')
                    ->label('Trạng thái')
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Lọc theo Lĩnh vực')
                    ->options([
                        'residence' => 'Cư trú & Hộ khẩu',
                        'vneid'     => 'Định danh VNeID',
                        'civil'     => 'Hộ tịch & Chứng thực',
                        'land'      => 'Đất đai & Xây dựng',
                        'social'    => 'An sinh xã hội',
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
            'index'  => Pages\ListProcedureVideos::route('/'),
            'create' => Pages\CreateProcedureVideo::route('/create'),
            'edit'   => Pages\EditProcedureVideo::route('/{record}/edit'),
        ];
    }
}
