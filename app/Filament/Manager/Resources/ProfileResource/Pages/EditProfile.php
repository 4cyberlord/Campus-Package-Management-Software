<?php

namespace App\Filament\Manager\Resources\ProfileResource\Pages;

use App\Filament\Manager\Resources\ProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class EditProfile extends EditRecord
{
    protected static string $resource = ProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Profile updated successfully');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Only update password if a new one is provided
        if (!empty($data['new_password'])) {
            $data['password'] = Hash::make($data['new_password']);
        }

        // Remove password fields from data
        unset(
            $data['new_password'],
            $data['new_password_confirmation']
        );

        return $data;
    }
}
