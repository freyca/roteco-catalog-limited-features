<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Products\ProductSpareParts\Forms;

use App\Filament\Admin\Resources\Products\Traits\FormBuilderTrait;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProducSparePartForm
{
    use FormBuilderTrait;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::mainSection(),

                Section::make(__('Disassembly'))
                    ->schema([
                        Select::make('disassembly_id')
                            ->required()
                            ->label(__('Disassembly'))
                            ->relationship(name: 'disassembly', titleAttribute: 'name')
                            ->columnSpanFull()
                            ->searchable()
                            ->preload(),
                        TextInput::make('number_in_image')
                            ->label(__('Number in image'))
                            ->required()
                            ->integer()
                            ->minValue(1),
                        TextInput::make('self_reference')
                            ->label(__('Self reference'))
                            ->nullable()
                            ->maxLength(255),
                    ]),

                self::priceSectionWithParentProduct(),
            ]);
    }
}
