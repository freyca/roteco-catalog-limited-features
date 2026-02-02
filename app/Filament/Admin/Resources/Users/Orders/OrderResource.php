<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Orders;

use App\Enums\OrderStatus;
use App\Filament\Admin\Resources\Users\Orders\Pages\CreateOrder;
use App\Filament\Admin\Resources\Users\Orders\Pages\EditOrder;
use App\Filament\Admin\Resources\Users\Orders\Pages\ListOrders;
use App\Filament\Admin\Resources\Users\Orders\Schemas\OrderForm;
use App\Filament\Admin\Resources\Users\Orders\Tables\OrdersTable;
use App\Models\Order;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    /**
     * @var array<string, mixed>
     */
    public array $product_options = [];

    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-euro';

    public static function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Order::query()->whereNotIn('status', [OrderStatus::Cancelled, OrderStatus::Delivered])->count();
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Users');
    }

    public static function getModelLabel(): string
    {
        return __('Orders');
    }
}
