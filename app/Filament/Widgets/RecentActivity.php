<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use App\Enums\PackageStatus;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class RecentActivity extends BaseWidget
{
    protected static ?string $heading = 'Recent Package Activities';
    protected static ?string $pollingInterval = '15s';
    protected static ?int $sort = 5;
    protected int $defaultPaginationPageOption = 10;
    protected string|int|array $columnSpan = 2;

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
                    ->with('user')
                    ->whereDate('updated_at', '>=', Carbon::now()->subDays(14))
                    ->orderBy('updated_at', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->user?->email ?? 'N/A')
                    ->wrap(),

                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('Tracking #')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Tracking number copied')
                    ->copyMessageDuration(1500),

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

                Tables\Columns\TextColumn::make('expected_pickup_date')
                    ->label('Pickup Date')
                    ->date()
                    ->sortable()
                    ->visible(fn ($record) => $record?->status === PackageStatus::READY_FOR_PICKUP),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->description(fn ($record) => $record->created_at?->diffForHumans() ?? 'N/A'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        PackageStatus::PENDING->value => 'Pending',
                        PackageStatus::RECEIVED->value => 'Received',
                        PackageStatus::READY_FOR_PICKUP->value => 'Ready For Pickup',
                        PackageStatus::PICKED_UP->value => 'Picked Up',
                    ]),
                Tables\Filters\SelectFilter::make('courier')
                    ->options([
                        'FedEx' => 'FedEx',
                        'UPS' => 'UPS',
                        'USPS' => 'USPS',
                        'DHL' => 'DHL',
                    ]),
            ])
            ->striped()
            ->poll('15s')
            ->paginated(true)
            ->defaultSort('updated_at', 'desc');
    }
}
