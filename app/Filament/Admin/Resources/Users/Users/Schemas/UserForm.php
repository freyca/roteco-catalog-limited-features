<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Users\Schemas;

use App\Enums\Role;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextInput::make('id')
                        ->disabled()
                        ->label('ID'),
                    ToggleButtons::make('role')
                        ->inline()
                        ->required()
                        ->default(Role::Customer->value)
                        ->options(Role::class),
                    TextInput::make('name')
                        ->required()
                        ->label(__('Name')),
                    TextInput::make('surname')
                        ->required()
                        ->label(__('Surname')),
                    TextInput::make('email')
                        ->required()
                        ->email(),
                    TextInput::make('password')
                        ->label(__('Password'))
                        ->password(),
                ])->columns(2),
            ]);
    }
}
