<?php

namespace App\Filament\Student\Widgets;

use App\Models\Package;
use App\Enums\PackageStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class MyPackagesOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $userId = Auth::id();

        return [
            Stat::make('Pending Packages', Package::where('user_id', $userId)
                ->where('status', PackageStatus::PENDING)
                ->count())
                ->description('Awaiting arrival')
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Ready For Pickup', Package::where('user_id', $userId)
                ->where('status', PackageStatus::READY_FOR_PICKUP)
                ->count())
                ->description('Available for collection')
                ->descriptionIcon('heroicon-m-hand-raised')
                ->color('warning')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Total Packages', Package::where('user_id', $userId)->count())
                ->description('All time packages')
                ->descriptionIcon('heroicon-m-inbox-stack')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
        ];
    }
}
