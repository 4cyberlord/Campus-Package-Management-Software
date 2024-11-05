<?php

namespace App\Filament\Admin\Resources\AllPackagesResource\Pages;

use App\Filament\Admin\Resources\AllPackagesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAllPackages extends ListRecords
{
    protected static string $resource = AllPackagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Remove create action since we don't want admins creating packages
        ];
    }
}
