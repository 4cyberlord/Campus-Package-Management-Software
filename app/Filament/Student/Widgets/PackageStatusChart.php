<?php

namespace App\Filament\Student\Widgets;

use App\Models\Package;
use App\Enums\PackageStatus;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class PackageStatusChart extends ChartWidget
{
    protected static ?string $heading = 'My Package Status Distribution';
    protected static ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $userId = Auth::id();
        $statuses = [
            PackageStatus::PENDING,
            PackageStatus::RECEIVED,
            PackageStatus::READY_FOR_PICKUP,
            PackageStatus::PICKED_UP
        ];

        $data = [];
        foreach ($statuses as $status) {
            $data[] = Package::where('user_id', $userId)
                ->where('status', $status)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Packages by Status',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgb(156, 163, 175)', // gray for pending
                        'rgb(59, 130, 246)', // blue for received
                        'rgb(245, 158, 11)', // orange for ready
                        'rgb(34, 197, 94)', // green for picked up
                    ],
                ],
            ],
            'labels' => ['Pending', 'Received', 'Ready For Pickup', 'Picked Up'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
