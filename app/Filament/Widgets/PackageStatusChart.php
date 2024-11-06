<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use App\Enums\PackageStatus;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PackageStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Package Status Distribution';
    protected static ?string $pollingInterval = '30s';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $statuses = [
            PackageStatus::PENDING,
            PackageStatus::RECEIVED,
            PackageStatus::READY_FOR_PICKUP,
            PackageStatus::PICKED_UP
        ];

        $data = [];
        foreach ($statuses as $status) {
            $data[] = Package::where('status', $status)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Packages by Status',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgb(255, 159, 64)', // pending
                        'rgb(54, 162, 235)', // received
                        'rgb(255, 205, 86)', // ready_for_pickup
                        'rgb(75, 192, 192)', // picked_up
                    ],
                ],
            ],
            'labels' => ['Pending', 'Received', 'Ready for Pickup', 'Picked Up'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
