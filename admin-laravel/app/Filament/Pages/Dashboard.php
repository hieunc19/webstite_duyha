<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Trang thống kê tổng quan';
    protected static ?string $navigationLabel = 'Trang thống kê';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?int $navigationSort = -10;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function getHeading(): string
    {
        return 'Thống kê tổng quan địa bàn Phường Duy Hà';
    }

    public function getSubheading(): ?string
    {
        return 'Báo cáo chỉ số phát triển, quy mô 10 tổ dân phố sau sáp nhập, đội ngũ cán bộ chuyên trách và mạng lưới cơ quan hành chính';
    }
}
