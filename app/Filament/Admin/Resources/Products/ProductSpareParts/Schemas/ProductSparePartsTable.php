<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Products\ProductSpareParts\Schemas;

use App\Filament\Admin\Imports\ProductSparePartImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ImportAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductSparePartsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make()
                    ->importer(ProductSparePartImporter::class),
            ])
            ->columns([
                TextColumn::make('id')
                    ->sortable(),

                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->label(__('Price'))
                    ->badge()
                    ->money(
                        currency: 'eur',
                        locale: 'es'
                    )
                    ->sortable(),

                TextColumn::make('price_with_discount')
                    ->label(__('Price with discount'))
                    ->badge()
                    ->money(
                        currency: 'eur',
                        locale: 'es'
                    )
                    ->sortable(),

                IconColumn::make('published')
                    ->label(__('Published'))
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->sortable()
                    ->date()
                    ->label(__('Creation date')),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ])
            ->defaultSort('id', 'desc');
    }
}
