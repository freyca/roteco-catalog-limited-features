<?php

declare(strict_types=1);

namespace App\Filament\User\Resources\Orders\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('Identifier')),
                TextColumn::make('purchase_cost')
                    ->label(__('Price'))
                    ->badge()
                    ->money(
                        currency: 'eur',
                        locale: 'es'
                    ),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
                TextColumn::make('payment_method')
                    ->label(__('Payment method'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Order date'))
                    ->sortable()
                    ->date(),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->filters([])
            ->toolbarActions([]);
    }
}
