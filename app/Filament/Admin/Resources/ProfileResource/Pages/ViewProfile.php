<?php

namespace App\Filament\Admin\Resources\ProfileResource\Pages;

use App\Filament\Admin\Resources\ProfileResource;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists\Infolist;

class ViewProfile extends Page
{
    protected static string $resource = ProfileResource::class;

    protected static string $view = 'filament.pages.profile';

    public $record;

    public function mount(): void
    {
        $this->record = Auth::user();
    }

    public function getTitle(): string
    {
        return 'My Profile';
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return static::getResource()::infolist($infolist)
            ->record($this->record);
    }

    protected function getActions(): array
    {
        return [
            Action::make('edit')
                ->url($this->getResource()::getUrl('edit', ['record' => Auth::id()]))
                ->icon('heroicon-o-pencil')
                ->label('Edit Profile')
                ->color('primary'),
        ];
    }
}
