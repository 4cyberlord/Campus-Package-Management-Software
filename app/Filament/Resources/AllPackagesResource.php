<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AllPackagesResource\Pages;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AllPackagesResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationLabel = 'All Packages';

    protected static ?string $modelLabel = 'Package';

    protected static ?string $pluralModelLabel = 'All Packages';

    protected static ?string $navigationGroup = 'Package Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Package Status')
                    ->description('Update package status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Current Status')
                            ->options([
                                'pending' => 'Pending',
                                'received' => 'Received',
                                'ready_for_pickup' => 'Ready for Pickup',
                                'picked_up' => 'Picked Up',
                            ])
                            ->required(),

                        Forms\Components\DatePicker::make('expected_pickup_date')
                            ->label('Expected Pickup Date')
                            ->required()
                            ->visible(fn ($get) => $get('status') === 'ready_for_pickup'),
                    ])->columns(2),

                Forms\Components\Section::make('Package Information')
                    ->schema([
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Tracking Number')
                            ->disabled(),

                        Forms\Components\TextInput::make('sender_name')
                            ->label('Sender Name')
                            ->disabled(),

                        Forms\Components\TextInput::make('courier')
                            ->disabled(),

                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Student')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('Tracking #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Tracking number copied'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sender_name')
                    ->label('From')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('courier')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'received' => 'info',
                        'ready_for_pickup' => 'warning',
                        'picked_up' => 'success',
                        default => 'gray',
                    }),

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
                    ->label('Update Status'),
            ])
            ->bulkActions([])
            ->emptyStateHeading('No Packages Found')
            ->emptyStateDescription('Packages will appear here once they are created.')
            ->emptyStateIcon('heroicon-o-inbox');
    }

    /* public static function getPages(): array
    {
        return [
            'index' => Pages\ListAllPackages::route('/'),
            'edit' => Pages\EditAllPackages::route('/{record}/edit'),
        ];
    } */
}
