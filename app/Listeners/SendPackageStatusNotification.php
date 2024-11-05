<?php

namespace App\Listeners;

use App\Events\PackageStatusChanged;
use App\Notifications\PackageStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendPackageStatusNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(PackageStatusChanged $event): void
    {
        try {
            $user = $event->package->user;

            if ($user) {
                $user->notify(new PackageStatusNotification(
                    $event->package,
                    $event->oldStatus,
                    $event->newStatus
                ));

                Log::info('Package status notification sent', [
                    'package_id' => $event->package->id,
                    'user_id' => $user->id,
                    'old_status' => $event->oldStatus,
                    'new_status' => $event->newStatus
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send package status notification', [
                'error' => $e->getMessage(),
                'package_id' => $event->package->id
            ]);
        }
    }
}
