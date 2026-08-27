<?php

namespace App\Filament\Widgets;

use App\Models\Neighborhood;
use App\Models\Official;
use App\Models\Place;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $newTdpCount = Neighborhood::where('type', 'new')->count() ?: 10;
        $totalHouseholds = Neighborhood::where('type', 'new')->sum('households') ?: 3546;
        $totalPeople = Neighborhood::where('type', 'new')->sum('people') ?: 14280;
        $totalArea = Neighborhood::where('type', 'new')->sum('area_ha') ?: 1546.30;
        $placeCount = Place::count() ?: 12;
        $officialCount = Official::count() ?: 18;

        return [
            Stat::make('Tổ dân phố mới', $newTdpCount . ' TDP')
                ->description('10 TDP hoàn thành sáp nhập')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('success'),

            Stat::make('Tổng số Hộ gia đình', number_format($totalHouseholds) . ' hộ')
                ->description('Quy mô dân cư toàn phường')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Tổng số Nhân khẩu', number_format($totalPeople) . ' người')
                ->description('Thống kê cư trú địa bàn')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Tổng diện tích tự nhiên', number_format($totalArea, 2) . ' ha')
                ->description('~15,46 km² diện tích toàn phường')
                ->descriptionIcon('heroicon-m-map')
                ->color('warning'),

            Stat::make('Cơ quan & Trụ sở hành chính', $placeCount . ' địa điểm')
                ->description('UBND, Công an, Y tế, Trường học...')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('success'),

            Stat::make('Cán bộ & CSKV phụ trách', $officialCount . ' đồng chí')
                ->description('Phụ trách 10 tổ dân phố')
                ->descriptionIcon('heroicon-m-identification')
                ->color('danger'),
        ];
    }
}
