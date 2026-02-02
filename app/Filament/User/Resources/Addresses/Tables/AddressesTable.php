<?php

declare(strict_types=1);

namespace App\Filament\User\Resources\Addresses\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AddressesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('address')
                    ->label(__('Address')),
                TextColumn::make('address_type')
                    ->label(__('Type'))
                    ->badge(),
                TextColumn::make('city')
                    ->label(__('City')),
                TextColumn::make('zip_code')
                    ->label(__('Zip code')),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([]);
    }
}
