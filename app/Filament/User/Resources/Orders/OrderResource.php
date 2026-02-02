<?php

declare(strict_types=1);

namespace App\Filament\User\Resources\Orders;

use App\Filament\User\Resources\Orders\Pages\ListOrders;
use App\Filament\User\Resources\Orders\Pages\ViewOrder;
use App\Filament\User\Resources\Orders\Schemas\OrderForm;
use App\Filament\User\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-m-shopping-bag';

    public static function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrdersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'view' => ViewOrder::route('/{record}'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Orders');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('User');
    }
}
