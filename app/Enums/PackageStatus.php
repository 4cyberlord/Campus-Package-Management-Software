<?php

namespace App\Enums;

enum PackageStatus: string
{
    case PENDING = 'pending';
    case RECEIVED = 'received';
    case READY_FOR_PICKUP = 'ready_for_pickup';
    case PICKED_UP = 'picked_up';

    public function getLabel(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::RECEIVED => 'Received',
            self::READY_FOR_PICKUP => 'Ready for Pickup',
            self::PICKED_UP => 'Picked Up',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::PENDING => 'gray',
            self::RECEIVED => 'info',
            self::READY_FOR_PICKUP => 'warning',
            self::PICKED_UP => 'success',
        };
    }

    public static function toArray(): array
    {
        return [
            self::PENDING->value => 'Pending',
            self::RECEIVED->value => 'Received',
            self::READY_FOR_PICKUP->value => 'Ready for Pickup',
            self::PICKED_UP->value => 'Picked Up',
        ];
    }
}
