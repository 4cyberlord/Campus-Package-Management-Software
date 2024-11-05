<?php

namespace App\Services;

use App\Models\Package;
use App\Models\Notification;

class NotificationService
{
    public static function notifyPackageStatusChange(Package $package, string $oldStatus, string $newStatus): void
    {
        $content = match ($newStatus) {
            'received' => "Your package (Tracking #: {$package->tracking_number}) has been received by the mailroom.",
            'ready_for_pickup' => "Your package (Tracking #: {$package->tracking_number}) is ready for pickup. Expected pickup date: {$package->expected_pickup_date}",
            'picked_up' => "Your package (Tracking #: {$package->tracking_number}) has been picked up.",
            default => "Your package (Tracking #: {$package->tracking_number}) status has been updated to {$newStatus}.",
        };

        Notification::create([
            'user_id' => $package->user_id,
            'package_id' => $package->id,
            'type' => 'Status Update',
            'content' => $content,
            'status' => 'sent',
            'channel' => 'database',
            'sent_at' => now(),
        ]);
    }
}
