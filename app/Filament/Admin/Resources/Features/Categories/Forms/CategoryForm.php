<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Features\Categories\Forms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextInput::make('id')
                        ->disabled(),
                    TextInput::make('name')
                        ->label(__('Name'))
                        ->required()
                        ->maxLength(255),
                ])->columns(2),

                FileUpload::make('big_image')
                    ->label(__('Big image'))
                    ->required()
                    ->moveFiles()
                    ->preserveFilenames()
                    ->orientImagesFromExif(false)
                    ->directory(config()->string('custom.category-image-storage')),

                // Published toggle
                Toggle::make('published')
                    ->label(__('Published')),
            ]);
    }
}
