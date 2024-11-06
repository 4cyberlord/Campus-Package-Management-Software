<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopStudents extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int $defaultPaginationPageOption = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::role('student')
                    ->withCount('packages')
                    ->orderBy('packages_count', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('packages_count')
                    ->label('Total Packages')
                    ->sortable()
                    ->badge(),
            ]);
    }
}
