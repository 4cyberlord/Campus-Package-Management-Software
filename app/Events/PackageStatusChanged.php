<?php

namespace App\Events;

use App\Models\Package;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PackageStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Package $package,
        public string $oldStatus,
        public string $newStatus
    ) {}
}
