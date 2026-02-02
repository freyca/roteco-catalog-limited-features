<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Products\Products\Schemas;

use App\Filament\Admin\Imports\ProductImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ImportAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make()
                    ->importer(ProductImporter::class),
            ])
            ->columns([
                TextColumn::make('id')
                    ->sortable(),

                ImageColumn::make('main_image')
                    ->circular()
                    ->label(__('Image')),

                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
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
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ])
            ->defaultSort('id', 'desc');
    }
}
