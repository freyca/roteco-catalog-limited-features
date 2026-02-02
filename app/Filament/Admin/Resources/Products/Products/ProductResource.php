<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Products\Products;

use App\Filament\Admin\Resources\Products\Products\Forms\ProductForm;
use App\Filament\Admin\Resources\Products\Products\Pages\CreateProduct;
use App\Filament\Admin\Resources\Products\Products\Pages\EditProduct;
use App\Filament\Admin\Resources\Products\Products\Pages\ListProducts;
use App\Filament\Admin\Resources\Products\Products\Schemas\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Product');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Products');
    }
}
