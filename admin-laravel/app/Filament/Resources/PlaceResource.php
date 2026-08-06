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
    
    protected static ?string $navigationLabel = 'Địa bàn Phường Duy Hà';
    protected static ?string $pluralLabel = 'Địa điểm địa bàn';
    protected static ?string $modelLabel = 'Địa điểm';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

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
                                Section::make('Thông tin địa điểm')
                                    ->columns(2)
                                    ->components([
                                        TextInput::make('name')
                                            ->label('Tên địa điểm')
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
                                            ->default(null),
                                        Hidden::make('administrative_unit_id'),
                                        FileUpload::make('image')
                                            ->label('Ảnh minh họa')
                                            ->image()
                                            ->disk('public')
                                            ->directory('places')
                                            ->default(null),
                                        Textarea::make('description')
                                            ->label('Mô tả chức năng nhiệm vụ')
                                            ->rows(6)
                                            ->columnSpanFull()
                                            ->default(null),
                                    ]),
                                Section::make('Vị trí')
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
                TextColumn::make('administrativeUnit.name')
                    ->label('Địa giới')
                    ->sortable()
                    ->searchable(),
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
                \Filament\Tables\Filters\SelectFilter::make('administrative_unit_id')
                    ->label('Địa giới hành chính')
                    ->relationship('administrativeUnit', 'name')
                    ->searchable()
                    ->preload(),
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
                EditAction::make(),
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
