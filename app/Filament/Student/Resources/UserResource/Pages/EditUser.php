<?php

namespace App\Filament\Student\Resources\UserResource\Pages;

use App\Filament\Student\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

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
        // If password was changed, redirect to login
        if (request()->has('new_password')) {
            Auth::logout();
            return route('filament.student.auth.login');
        }

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
            $data['current_password'],
            $data['new_password'],
            $data['new_password_confirmation']
        );

        return $data;
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');
            $data = $this->form->getState();
            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            // Handle password update separately
            if (isset($data['password'])) {
                $this->record->forceFill([
                    'password' => $data['password']
                ])->save();

                unset($data['password']);
            }

            // Update other fields
            $this->record->update($data);

            $this->callHook('afterSave');

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error updating profile')
                ->body($e->getMessage())
                ->send();

            return;
        }

        if ($shouldSendSavedNotification) {
            $this->getSavedNotification()?->send();
        }

        if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
            $this->redirect($redirectUrl);
        }
    }
}
