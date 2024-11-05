<?php

namespace App\Filament\Student\Resources\NotificationResource\Pages;

use App\Filament\Student\Resources\NotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

class ViewNotification extends ViewRecord
{
    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('mark_as_read')
                ->icon('heroicon-o-check')
                ->visible(fn () => is_null($this->record->read_at))
                ->action(function () {
                    $this->record->markAsRead();
                    $this->notify('success', 'Notification marked as read');
                }),
        ];
    }
}
