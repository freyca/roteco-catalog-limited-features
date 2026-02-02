<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Features\Categories;

use App\Filament\Admin\Resources\Features\Categories\Forms\CategoryForm;
use App\Filament\Admin\Resources\Features\Categories\Pages\CreateCategory;
use App\Filament\Admin\Resources\Features\Categories\Pages\EditCategory;
use App\Filament\Admin\Resources\Features\Categories\Pages\ListCategories;
use App\Filament\Admin\Resources\Features\Categories\Schemas\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Features');
    }

    public static function getModelLabel(): string
    {
        return __('Categories');
    }
}
