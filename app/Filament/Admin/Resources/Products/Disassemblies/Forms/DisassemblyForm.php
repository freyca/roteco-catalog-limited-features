<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Products\Disassemblies\Forms;

use App\Filament\Admin\Resources\Products\Traits\FormBuilderTrait;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DisassemblyForm
{
    use FormBuilderTrait;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('id')
                        ->disabled(),

                    TextInput::make('name')
                        ->label(__('Name'))
                        ->required()
                        ->maxLength(255),
                ])->columns(2),

                Section::make(__('Product'))->schema([
                    Select::make('product_id')
                        ->label(__('Product'))
                        ->required()
                        ->relationship(name: 'product', titleAttribute: 'name')
                        ->columnSpanFull()
                        ->searchable()
                        ->preload(),
                ]),

                self::imagesSection(),

                Section::make(__('Spare parts'))->schema([
                    Repeater::make('productSpareParts')
                        ->label(__('Product spare parts'))
                        ->relationship()
                        ->schema([
                            self::mainSection(),

                            TextInput::make('number_in_image')
                                ->label(__('Number in image'))
                                ->required()
                                ->integer()
                                ->minValue(1),

                            self::priceSectionWithParentProduct(),
                        ]),
                ]),
            ]);
    }
}
