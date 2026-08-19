<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlaceResource\Pages\ManagePlaces;
use App\Models\Place;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlaceResource extends Resource
{
    protected static ?string $model = Place::class;
    
    protected static ?string $navigationLabel = 'Địa điểm & Trụ sở';
    protected static ?string $pluralLabel = 'Địa điểm & Trụ sở';
    protected static ?string $modelLabel = 'Địa điểm';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    public static function getNavigationGroup(): ?string
    {
        return 'Quản lý Địa bàn & Dân cư';
    }

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Chi tiết địa điểm')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Thông tin chung & Bản đồ')
                            ->icon('heroicon-o-information-circle')
                            ->components([
                                Grid::make(['default' => 1, 'lg' => 2])
                                    ->schema([
                                        Section::make('Thông tin địa điểm')
                                            ->columnSpan(1)
                                            ->columns(2)
                                            ->components([
                                                TextInput::make('name')
                                                    ->label('Tên địa điểm')
                                                    ->columnSpanFull()
                                                    ->required(),
                                                Select::make('category')
                                                    ->label('Phân loại')
                                                    ->options([
                                                        'government' => 'Cơ quan đoàn thể / Hành chính',
                                                        'police' => 'Trụ sở Công an',
                                                        'neighborhood' => 'Tổ dân phố / Nhà văn hóa',
                                                        'school' => 'Trường học / Giáo dục',
                                                        'health' => 'Y tế / Bệnh viện / Trạm y tế',
                                                        'meritorious_family' => 'Gia đình có công với cách mạng',
                                                    ])
                                                    ->required()
                                                    ->default('government'),
                                                Select::make('status')
                                                    ->label('Trạng thái')
                                                    ->options([
                                                        'active' => 'Hoạt động',
                                                        'closed' => 'Tạm đóng',
                                                    ])
                                                    ->required()
                                                    ->default('active'),
                                                TextInput::make('address')
                                                    ->label('Địa chỉ chi tiết')
                                                    ->columnSpanFull()
                                                    ->default(null),
                                                TextInput::make('phone')
                                                    ->label('Số điện thoại / Hotline')
                                                    ->tel()
                                                    ->placeholder('Ví dụ: 0226.3835.112 hoặc 0988.xxx.xxx')
                                                    ->columnSpanFull()
                                                    ->default(null),
                                                Hidden::make('administrative_unit_id'),
                                                FileUpload::make('image')
                                                    ->label('Ảnh minh họa')
                                                    ->image()
                                                    ->disk('public')
                                                    ->directory('places')
                                                    ->imagePreviewHeight('180')
                                                    ->openable()
                                                    ->downloadable()
                                                    ->columnSpanFull()
                                                    ->default(null),
                                                Textarea::make('description')
                                                    ->label('Mô tả chức năng nhiệm vụ')
                                                    ->rows(4)
                                                    ->columnSpanFull()
                                                    ->default(null),
                                            ]),
                                        Section::make('Vị trí & Bản đồ')
                                            ->columnSpan(1)
                                            ->components([
                                                TextInput::make('coordinates')
                                                    ->label('Tọa độ (Vĩ độ, Kinh độ)')
                                                    ->placeholder('ví dụ: 20.6478448, 105.914737')
                                                    ->reactive()
                                                    ->dehydrated(false)
                                                    ->suffixAction(
                                                        Action::make('useCurrentLocation')
                                                            ->icon('heroicon-m-map-pin')
                                                            ->tooltip('Lấy vị trí hiện tại')
                                                            ->alpineClickHandler('$dispatch(\'use-current-location\')'),
                                                        isInline: true,
                                                    )
                                                    ->afterStateHydrated(function ($set, $record) {
                                                        if ($record) {
                                                            $set('coordinates', "{$record->lat},{$record->lng}");
                                                        }
                                                    })
                                                    ->afterStateUpdated(function ($state, $set) {
                                                        if (blank($state)) {
                                                            $set('lat', null);
                                                            $set('lng', null);
                                                            $set('administrative_unit_id', null);
                                                            return;
                                                        }

                                                        if (str_contains($state, ',')) {
                                                            [$lat, $lng] = explode(',', $state);
                                                            $lat = trim($lat);
                                                            $lng = trim($lng);
                                                            $set('lat', $lat);
                                                            $set('lng', $lng);

                                                            // Auto detect admin unit
                                                            $unitId = \App\Services\GeoService::findAdminUnitByCoordinates((float)$lat, (float)$lng);
                                                            if ($unitId) {
                                                                $set('administrative_unit_id', $unitId);
                                                            }
                                                        }
                                                    })
                                                    ->live(onBlur: true),
                                                Hidden::make('lat'),
                                                Hidden::make('lng'),
                                                ViewField::make('map')
                                                    ->label('Chọn vị trí trên bản đồ')
                                                    ->view('filament.forms.components.map-picker')
                                                    ->dehydrated(false),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('stt')
                    ->label('STT')
                    ->rowIndex(),
                TextColumn::make('name')
                    ->label('Tên địa điểm')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('category')
                    ->label('Phân loại')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'government' => 'Cơ quan đoàn thể / Hành chính',
                        'police' => 'Trụ sở Công an',
                        'neighborhood' => 'Tổ dân phố / Nhà văn hóa',
                        'school' => 'Trường học / Giáo dục',
                        'health' => 'Y tế / Bệnh viện / Trạm y tế',
                        'meritorious_family' => 'Gia đình có công với cách mạng',
                        default => $state,
                    })
                    ->badge()
                    ->colors([
                        'danger' => 'government',
                        'info' => 'police',
                        'warning' => 'neighborhood',
                        'success' => 'school',
                        'primary' => 'health',
                        'danger' => 'meritorious_family',
                    ])
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Số điện thoại / Hotline')
                    ->searchable()
                    ->placeholder('Chưa cập nhật'),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hoạt động',
                        'closed' => 'Tạm đóng',
                        default => $state,
                    })
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'danger' => 'closed',
                    ])
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('category')
                    ->label('Phân loại')
                    ->options([
                        'government' => 'Cơ quan đoàn thể / Hành chính',
                        'police' => 'Trụ sở Công an',
                        'neighborhood' => 'Tổ dân phố / Nhà văn hóa',
                        'school' => 'Trường học / Giáo dục',
                        'health' => 'Y tế / Bệnh viện / Trạm y tế',
                        'meritorious_family' => 'Gia đình có công với cách mạng',
                    ]),
            ])
            ->recordActions([
                EditAction::make()->modalWidth('7xl'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePlaces::route('/'),
        ];
    }
}
