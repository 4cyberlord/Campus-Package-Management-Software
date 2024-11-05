<?php

namespace App\Notifications;

use App\Models\Package;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class PackageStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Package $package,
        protected string $oldStatus,
        protected string $newStatus
    ) {
        Log::info('Creating notification', [
            'package_id' => $package->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $statusMessage = match ($this->newStatus) {
            'received' => "Your package (#{$this->package->tracking_number}) has been received at the mailroom.",
            'ready_for_pickup' => "Your package (#{$this->package->tracking_number}) is ready for pickup.",
            'picked_up' => "Your package (#{$this->package->tracking_number}) has been picked up.",
            default => "Your package (#{$this->package->tracking_number}) status has been updated to {$this->newStatus}."
        };

        Log::info('Creating database notification', [
            'user_id' => $notifiable->id,
            'package_id' => $this->package->id,
            'message' => $statusMessage
        ]);

        return [
            'id' => $this->id,
            'package_id' => $this->package->id,
            'tracking_number' => $this->package->tracking_number,
            'message' => $statusMessage,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'time' => now()->toDateTimeString(),
        ];
    }
}
