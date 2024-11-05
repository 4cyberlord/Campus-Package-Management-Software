<?php

namespace App\Filament\Student\Resources\NotificationResource\Pages;

use App\Filament\Student\Resources\NotificationResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListNotifications extends ListRecords
{
    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
