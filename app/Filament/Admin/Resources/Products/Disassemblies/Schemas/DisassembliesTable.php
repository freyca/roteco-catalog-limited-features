<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Products\Disassemblies\Schemas;

use App\Filament\Admin\Imports\DisassemblyImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ImportAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DisassembliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make()
                    ->importer(DisassemblyImporter::class),
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
