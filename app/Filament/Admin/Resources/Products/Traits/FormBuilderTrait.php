<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Products\Traits;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

trait FormBuilderTrait
{
    private static function mainSection(): Section
    {
        return Section::make()->schema([
            Toggle::make('published')
                ->label(__('Visible on shop'))
                ->helperText(__('If off, this product will be hidden from the shop.'))
                ->columnSpan('full')
                ->default(false),

            TextInput::make('id')
                ->disabled(),

            TextInput::make('reference')
                ->label(__('Reference'))
                ->required()
                ->unique()
                ->maxLength(255),

            TextInput::make('name')
                ->label(__('Name'))
                ->required()
                ->maxLength(255),

        ])->columns(2);
    }

    private static function priceSectionWithParentProduct(): Section
    {
        return Section::make(__('Pricing'))
            ->schema([
                TextInput::make('price')
                    ->label(__('Price PVP'))
                    ->numeric()
                    ->suffix('€')
                    ->required(),

                TextInput::make('price_with_discount')
                    ->label(__('Price to retailer'))
                    ->suffix('€')
                    ->required()
                    ->numeric(),

            ])->columns(2);
    }

    private static function imagesSection(): Section
    {
        return Section::make(__('Images'))
            ->schema([
                FileUpload::make('main_image')
                    ->label(__('Main image'))
                    ->required()
                    ->reorderable()
                    ->moveFiles()
                    ->orientImagesFromExif(false)
                    ->preserveFilenames()
                    ->directory(config()->string('custom.product-image-storage')),
            ])->columns(2);
    }
}
