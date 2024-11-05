<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\PackageResource\Pages;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Enums\PackageStatus;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationLabel = 'My Packages';

    protected static ?string $modelLabel = 'Package';

    protected static ?string $pluralModelLabel = 'Packages';

    protected static ?string $navigationGroup = 'Package Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(Auth::id()),

                Forms\Components\Section::make('Package Information')
                    ->description('Enter details about your package')
                    ->schema([
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Tracking Number')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('sender_name')
                            ->label('Sender Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('courier')
                            ->options([
                                'FedEx' => 'FedEx',
                                'UPS' => 'UPS',
                                'USPS' => 'USPS',
                                'DHL' => 'DHL',
                            ])
                            ->required(),

                        Forms\Components\Select::make('package_type')
                            ->label('Package Type')
                            ->options([
                                'letter' => 'Letter',
                                'box' => 'Box',
                                'parcel' => 'Parcel',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),

                // Status will be set to 'pending' by default in the migration
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
                    ->copyMessage('Tracking number copied')
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('sender_name')
                    ->label('From')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('courier')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (PackageStatus $state): string => $state->getLabel())
                    ->color(fn (PackageStatus $state): string => $state->getColor()),

                Tables\Columns\TextColumn::make('expected_pickup_date')
                    ->label('Pickup Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\IconColumn::make('authorized_pickup')
                    ->label('Auth. Pickup')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received On')
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
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->emptyStateHeading('No Packages Yet')
            ->emptyStateDescription('When you receive packages, they will appear here.')
            ->emptyStateIcon('heroicon-o-inbox');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackages::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id())
            ->latest();
    }
}
