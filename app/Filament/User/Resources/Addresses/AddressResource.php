<?php

declare(strict_types=1);

namespace App\Filament\User\Resources\Addresses;

use App\Filament\User\Resources\Addresses\Pages\CreateAddress;
use App\Filament\User\Resources\Addresses\Pages\EditAddress;
use App\Filament\User\Resources\Addresses\Pages\ListAddress;
use App\Filament\User\Resources\Addresses\Schemas\AddressForm;
use App\Filament\User\Resources\Addresses\Tables\AddressesTable;
use App\Models\Address;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return AddressForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AddressesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateAddress::route('/create'),
            'index' => ListAddress::route('/'),
            'edit' => EditAddress::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Addresses');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('User');
    }
}
