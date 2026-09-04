<?php

namespace App\Filament\Resources;

use App\Models\Neighborhood;
use App\Models\WasteSchedule;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WasteScheduleResource extends Resource
{
    protected static ?string $model = WasteSchedule::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-trash';

    protected static ?string $navigationLabel = 'Lịch thu gom rác';

    protected static ?string $modelLabel = 'Lịch thu gom rác';

    protected static ?string $pluralModelLabel = 'Lịch thu gom rác sinh hoạt';

    public static function getNavigationGroup(): ?string
    {
        return 'Quản lý Địa bàn & Dân cư';
    }

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin Lịch thu gom rác theo Tổ dân phố')
                    ->description('Cấu hình khung giờ xe gom rác và các ngày thu gom trong tuần cho từng Tổ dân phố')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        Select::make('tdp_name')
                            ->label('Tên Tổ dân phố')
                            ->placeholder('-- Chọn Tổ dân phố --')
                            ->required()
                            ->searchable()
                            ->options(function ($record) {
                                $existingQuery = WasteSchedule::query();
                                if ($record) {
                                    $existingQuery->where('id', '!=', $record->id);
                                }
                                $takenNames = $existingQuery->pluck('tdp_name')->toArray();

                                $allTdps = Neighborhood::where('type', 'new')
                                    ->orderBy('id', 'asc')
                                    ->pluck('name', 'name')
                                    ->toArray();

                                if (empty($allTdps)) {
                                    $allTdps = [
                                        'TDP Duy Minh' => 'TDP Duy Minh',
                                        'TDP Ngọc Tú' => 'TDP Ngọc Tú',
                                        'TDP Động Linh Trang' => 'TDP Động Linh Trang',
                                        'TDP Chuông' => 'TDP Chuông',
                                        'TDP Bạch Xá' => 'TDP Bạch Xá',
                                        'TDP Hoàng Đông' => 'TDP Hoàng Đông',
                                        'TDP Hương Cát' => 'TDP Hương Cát',
                                        'TDP Duy Hải' => 'TDP Duy Hải',
                                        'TDP Ngọc Động' => 'TDP Ngọc Động',
                                        'TDP Đông Hải' => 'TDP Đông Hải',
                                    ];
                                }

                                return array_filter($allTdps, function ($name) use ($takenNames) {
                                    return !in_array($name, $takenNames);
                                });
                            })
                            ->columnSpanFull(),

                        TextInput::make('morning_shift')
                            ->label('Khung giờ thu gom rác')
                            ->placeholder('Ví dụ: 05h30 - 07h00 hoặc 17h00 - 18h30')
                            ->helperText('1 khung giờ duy nhất áp dụng cho các ngày thu gom trong tuần của TDP này.')
                            ->columnSpanFull()
                            ->required(),

                        CheckboxList::make('collection_days')
                            ->label('Các ngày thu gom rác trong tuần')
                            ->options([
                                'thu_2'    => 'Thứ 2',
                                'thu_3'    => 'Thứ 3',
                                'thu_4'    => 'Thứ 4',
                                'thu_5'    => 'Thứ 5',
                                'thu_6'    => 'Thứ 6',
                                'thu_7'    => 'Thứ 7',
                                'chu_nhat' => 'Chủ nhật',
                            ])
                            ->columns(4)
                            ->default(['thu_2', 'thu_5'])
                            ->required()
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

                TextColumn::make('tdp_name')
                    ->label('Tổ dân phố')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('morning_shift')
                    ->label('Khung giờ thu gom')
                    ->placeholder('—')
                    ->badge()
                    ->color('success'),

                TextColumn::make('collection_days')
                    ->label('Các ngày thu gom')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(function ($state) {
                        $dayMap = [
                            'thu_2' => 'Thứ 2',
                            'thu_3' => 'Thứ 3',
                            'thu_4' => 'Thứ 4',
                            'thu_5' => 'Thứ 5',
                            'thu_6' => 'Thứ 6',
                            'thu_7' => 'Thứ 7',
                            'chu_nhat' => 'Chủ nhật',
                        ];
                        if (is_array($state)) {
                            return array_map(fn($d) => $dayMap[$d] ?? $d, $state);
                        }
                        return $dayMap[$state] ?? $state ?? 'Chưa chọn';
                    }),

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
            'index' => WasteScheduleResource\Pages\ListWasteSchedules::route('/'),
            'create' => WasteScheduleResource\Pages\CreateWasteSchedule::route('/create'),
            'edit' => WasteScheduleResource\Pages\EditWasteSchedule::route('/{record}/edit'),
        ];
    }
}
