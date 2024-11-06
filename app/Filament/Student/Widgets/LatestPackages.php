<?php

namespace App\Filament\Student\Widgets;

use App\Models\Package;
use App\Enums\PackageStatus;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class LatestPackages extends BaseWidget
{
    protected static ?string $heading = 'My Recent Packages';
    protected int $defaultPaginationPageOption = 5;

    protected function getStatusLabel(PackageStatus $status): string
    {
        return match ($status) {
            PackageStatus::PENDING => 'Pending',
            PackageStatus::RECEIVED => 'Received',
            PackageStatus::READY_FOR_PICKUP => 'Ready For Pickup',
            PackageStatus::PICKED_UP => 'Picked Up',
            default => Str::title(str_replace('_', ' ', $status->value))
        };
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Package::query()
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('Tracking #')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Tracking number copied'),

                Tables\Columns\TextColumn::make('courier')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (PackageStatus $state): string => $this->getStatusLabel($state))
                    ->color(fn (PackageStatus $state): string => match ($state) {
                        PackageStatus::PENDING => 'gray',
                        PackageStatus::RECEIVED => 'info',
                        PackageStatus::READY_FOR_PICKUP => 'warning',
                        PackageStatus::PICKED_UP => 'success',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
            ])
            ->striped()
            ->poll('15s');
    }
}
