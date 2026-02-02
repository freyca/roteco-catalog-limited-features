<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Products\Products\Forms;

use App\Filament\Admin\Resources\Products\Traits\FormBuilderTrait;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    use FormBuilderTrait;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::mainSection(),

                Section::make(__('Category'))
                    ->schema([
                        Select::make('category_id')
                            ->required()
                            ->label(__('Category'))
                            ->relationship(name: 'category', titleAttribute: 'name')
                            ->columnSpanFull()
                            ->searchable()
                            ->preload()
                            ->createOptionForm(
                                [
                                    Section::make([
                                        TextInput::make('name')
                                            ->label(__('Name'))
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                        ->columns(2),

                                    FileUpload::make('big_image')
                                        ->label(__('Big image'))
                                        ->required()
                                        ->moveFiles()
                                        ->orientImagesFromExif(false)
                                        ->preserveFilenames()
                                        ->directory(config()->string('custom.category-image-storage')),
                                ]
                            )
                            ->createOptionAction(fn (Action $action): Action => $action
                                ->modalHeading(__('Create category'))
                                ->modalSubmitActionLabel('Create category'))->columnSpan(1),
                    ]),

                self::imagesSection(),

                Section::make(__('Disassemblies'))->schema([
                    Repeater::make('disassemblies')
                        ->label(__('Disassembly'))
                        ->relationship()
                        ->schema([
                            TextInput::make('name')
                                ->label(__('Name'))
                                ->required()
                                ->maxLength(255)
                                ->live(),

                            FileUpload::make('main_image')
                                ->label(__('Main image'))
                                ->required()
                                ->reorderable()
                                ->moveFiles()
                                ->orientImagesFromExif(false)
                                ->preserveFilenames()
                                ->directory(config()->string('custom.product-image-storage')),
                        ])
                        ->columns(2)
                        ->collapsed(),
                ]),

            ]);
    }
}
