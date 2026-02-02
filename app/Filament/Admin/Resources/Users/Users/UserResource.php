<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Users;

use App\Filament\Admin\Resources\Users\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Users\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Users\Users\Pages\ListUsers;
use App\Filament\Admin\Resources\Users\Users\RelationManagers\AddressRelationManager;
use App\Filament\Admin\Resources\Users\Users\Schemas\UserForm;
use App\Filament\Admin\Resources\Users\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Users');
    }

    public static function getModelLabel(): string
    {
        return __('User');
    }
}
