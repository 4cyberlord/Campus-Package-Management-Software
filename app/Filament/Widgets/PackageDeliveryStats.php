<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use App\Enums\PackageStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class PackageDeliveryStats extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            Stat::make('Today\'s Deliveries', Package::whereDate('created_at', $today)->count())
                ->description('Packages received today')
                ->descriptionIcon('heroicon-m-truck')
                ->color('success'),

            Stat::make('Ready for Pickup', Package::where('status', PackageStatus::READY_FOR_PICKUP)->count())
                ->description('Awaiting student pickup')
                ->descriptionIcon('heroicon-m-hand-raised')
                ->color('warning'),

            Stat::make('Picked Up This Week', Package::where('status', PackageStatus::PICKED_UP)
                ->where('updated_at', '>=', $thisWeek)
                ->count())
                ->description('Successfully delivered')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
