<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Features\Categories\Schemas;

use App\Filament\Admin\Imports\CategoryImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ImportAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make()
                    ->importer(CategoryImporter::class),
            ])
            ->columns([
                TextColumn::make('id')
                    ->sortable(),

                ImageColumn::make('big_image')
                    ->circular()
                    ->label(__('Image')),

                TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),

                IconColumn::make('published')
                    ->boolean()
                    ->label(__('Published')),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }
}
