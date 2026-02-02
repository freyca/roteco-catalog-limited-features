<?php

declare(strict_types=1);

namespace App\Filament\User\Resources\Addresses\Schemas;

use App\Enums\AddressType;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class AddressForm
{
    public static function configure(Schema $schema): Schema
    {
        /**
         * @var User
         */
        $user = Auth::user();

        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->default($user->name)
                    ->maxLength(255),
                TextInput::make('surname')
                    ->label(__('Surname'))
                    ->default($user->surname)
                    ->required()
                    ->maxLength(255),
                ToggleButtons::make('address_type')
                    ->label(__('Address type'))
                    ->default(AddressType::Shipping)
                    ->inline()
                    ->required()
                    ->options(AddressType::class),
                TextInput::make('business_name')
                    ->label(__('Business Name').' ('.__('optional').')')
                    ->maxLength(255),
                TextInput::make('financial_number')
                    ->label(__('Financial Number').' ('.__('optional').')')
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label(__('Phone'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label(__('Email'))
                    ->required()
                    ->email()
                    ->default($user->email)
                    ->maxLength(255),
                TextInput::make('address')
                    ->label(__('Address'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('city')
                    ->label(__('City'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('state')
                    ->label(__('State'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('zip_code')
                    ->label(__('Zip code'))
                    ->required()
                    ->numeric()
                    ->integer()
                    ->maxLength(255),
                TextInput::make('country')
                    ->label(__('Country'))
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
