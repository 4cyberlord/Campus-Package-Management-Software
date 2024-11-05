<?php

namespace App\Providers;

use App\Events\PackageStatusChanged;
use App\Listeners\SendPackageStatusNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PackageStatusChanged::class => [
            SendPackageStatusNotification::class,
        ],
    ];
}
