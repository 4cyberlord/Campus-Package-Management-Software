<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\NotificationResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Filament\Notifications\Notification;

class NotificationResource extends Resource
{
    protected static ?string $model = DatabaseNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationLabel = 'My Notifications';

    protected static ?string $modelLabel = 'Notification';

    protected static ?string $navigationGroup = 'Communications';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()
            ->where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()))
            ->whereNull('read_at')
            ->count() ?: null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('data.message')
                    ->label('Message')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('read_at')
                    ->label('Read')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),
            ])
            ->filters([
                Tables\Filters\Filter::make('unread')
                    ->query(fn (Builder $query): Builder => $query->whereNull('read_at')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('mark_as_read')
                    ->icon('heroicon-o-check')
                    ->visible(fn ($record) => is_null($record->read_at))
                    ->requiresConfirmation(false)
                    ->action(function ($record) {
                        $record->markAsRead();

                        Notification::make()
                            ->success()
                            ->title('Notification marked as read')
                            ->send();
                    }),
            ])
            ->bulkActions([])
            ->emptyStateHeading('No Notifications')
            ->emptyStateDescription('You have no notifications at this time.')
            ->emptyStateIcon('heroicon-o-bell-slash')
            ->poll('1s'); // Poll every second
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            'view' => Pages\ViewNotification::route('/{record}'),
        ];
    }
}
