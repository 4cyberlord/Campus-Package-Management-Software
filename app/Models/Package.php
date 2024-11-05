<?php

namespace App\Models;

use App\Enums\PackageStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\DatabaseNotification;

class Package extends Model
{
    protected $fillable = [
        'user_id',
        'tracking_number',
        'sender_name',
        'courier',
        'package_type',
        'description',
        'status',
        'expected_pickup_date',
        'received_at',
        'picked_up_at',
        'authorized_pickup',
        'authorized_person_name',
        'authorized_person_id',
    ];

    protected $casts = [
        'expected_pickup_date' => 'date',
        'received_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'authorized_pickup' => 'boolean',
        'status' => PackageStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable');
    }
}
