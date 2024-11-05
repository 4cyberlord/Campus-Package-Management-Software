<?php

namespace App\Filament\Admin\Resources\AllPackagesResource\Pages;

use App\Filament\Admin\Resources\AllPackagesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Notifications\PackageStatusNotification;
use Illuminate\Support\Facades\DB;

class EditAllPackages extends EditRecord
{
    protected static string $resource = AllPackagesResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function beforeSave(): void
    {
        // Store the original status before saving
        $this->oldStatus = $this->record->getOriginal('status');
    }

    protected function afterSave(): void
    {
        // Only send notification if status has changed
        if ($this->oldStatus !== $this->record->status) {
            DB::transaction(function () {
                // Send notification to the user
                $this->record->user->notify(new PackageStatusNotification(
                    $this->record,
                    $this->oldStatus,
                    $this->record->status
                ));

                // Show success message
                Notification::make()
                    ->success()
                    ->title('Status Updated')
                    ->body("Package status updated and notification sent")
                    ->send();
            });
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
