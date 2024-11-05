<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AllPackagesResource\Pages;
use App\Models\Package;
use App\Notifications\PackageStatusNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use App\Enums\PackageStatus;

class AllPackagesResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationLabel = 'All Packages';

    protected static ?string $modelLabel = 'Package';

    protected static ?string $pluralModelLabel = 'All Packages';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = null;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Update Package Status')
                    ->description('Change the current status of the package')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Current Status')
                            ->options(PackageStatus::toArray())
                            ->enum(PackageStatus::class)
                            ->required(),

                        Forms\Components\DatePicker::make('expected_pickup_date')
                            ->label('Expected Pickup Date')
                            ->required()
                            ->visible(fn (Forms\Get $get) => $get('status') === 'ready_for_pickup'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('Tracking #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Tracking number copied'),

                Tables\Columns\TextColumn::make('sender_name')
                    ->label('From')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('courier')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('package_type')
                    ->label('Type'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (PackageStatus $state): string => $state->getLabel())
                    ->color(fn (PackageStatus $state): string => $state->getColor()),

                Tables\Columns\TextColumn::make('expected_pickup_date')
                    ->label('Pickup Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'received' => 'Received',
                        'ready_for_pickup' => 'Ready for Pickup',
                        'picked_up' => 'Picked Up',
                    ]),
                Tables\Filters\SelectFilter::make('courier')
                    ->options([
                        'FedEx' => 'FedEx',
                        'UPS' => 'UPS',
                        'USPS' => 'USPS',
                        'DHL' => 'DHL',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Update Status')
                    ->modalHeading('Update Package Status')
                    ->modalDescription('Change the status of this package'),
            ])
            ->bulkActions([])
            ->emptyStateHeading('No Packages Found')
            ->emptyStateDescription('Packages will appear here once students create them.')
            ->emptyStateIcon('heroicon-o-inbox');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAllPackages::route('/'),
            'edit' => Pages\EditAllPackages::route('/{record}/edit'),
        ];
    }
}
