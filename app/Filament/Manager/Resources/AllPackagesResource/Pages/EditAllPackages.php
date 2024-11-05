<?php

namespace App\Filament\Manager\Resources\AllPackagesResource\Pages;

use App\Filament\Manager\Resources\AllPackagesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAllPackages extends EditRecord
{
    protected static string $resource = AllPackagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Remove DeleteAction from here
        ];
    }
}
