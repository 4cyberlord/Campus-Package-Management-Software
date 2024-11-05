<?php

namespace App\Filament\Manager\Resources\AllPackagesResource\Pages;

use App\Filament\Manager\Resources\AllPackagesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAllPackages extends ListRecords
{
    protected static string $resource = AllPackagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
