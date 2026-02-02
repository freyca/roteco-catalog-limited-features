<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Products\ProductSpareParts;

use App\Filament\Admin\Resources\Products\ProductSpareParts\Forms\ProducSparePartForm;
use App\Filament\Admin\Resources\Products\ProductSpareParts\Pages\CreateProductSparePart;
use App\Filament\Admin\Resources\Products\ProductSpareParts\Pages\EditProductSparePart;
use App\Filament\Admin\Resources\Products\ProductSpareParts\Pages\ListProductSpareParts;
use App\Filament\Admin\Resources\Products\ProductSpareParts\Schemas\ProductSparePartsTable;
use App\Models\ProductSparePart;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ProductSparePartResource extends Resource
{
    protected static ?string $model = ProductSparePart::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-s-wrench';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ProducSparePartForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductSparePartsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductSpareParts::route('/'),
            'create' => CreateProductSparePart::route('/create'),
            'edit' => EditProductSparePart::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Products');
    }

    public static function getModelLabel(): string
    {
        return __('Spare parts');
    }
}
