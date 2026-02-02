<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Products\Disassemblies;

use App\Filament\Admin\Resources\Products\Disassemblies\Forms\DisassemblyForm;
use App\Filament\Admin\Resources\Products\Disassemblies\Pages\CreateDisassembly;
use App\Filament\Admin\Resources\Products\Disassemblies\Pages\EditDisassembly;
use App\Filament\Admin\Resources\Products\Disassemblies\Pages\ListDisassemblies;
use App\Filament\Admin\Resources\Products\Disassemblies\Schemas\DisassembliesTable;
use App\Models\Disassembly;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class DisassemblyResource extends Resource
{
    protected static ?string $model = Disassembly::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-s-wrench-screwdriver';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return DisassemblyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DisassembliesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDisassemblies::route('/'),
            'edit' => EditDisassembly::route('/{record}/edit'),
            'create' => CreateDisassembly::route('/create'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Disassembly');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Products');
    }
}
