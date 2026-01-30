<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Orders;

use App\DTO\OrderProductDTO;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Filament\Admin\Exports\OrderExporter;
use App\Filament\Admin\Resources\Users\Orders\Pages\CreateOrder;
use App\Filament\Admin\Resources\Users\Orders\Pages\EditOrder;
use App\Filament\Admin\Resources\Users\Orders\Pages\ListOrders;
use App\Models\Address;
use App\Models\BaseProduct;
use App\Models\Order;
use App\Models\ProductSparePart;
use App\Models\User;
use App\Services\PriceCalculator;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
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
        return $schema
            ->components([
                Section::make([
                    TextInput::make('id')
                        ->name(__('Order id (automatically generated)') . ':')
                        ->disabled()
                        ->columnSpanFull(),

                    Section::make(__('Customer data'))
                        ->schema([
                            Select::make('user_id')
                                ->relationship('user', 'email')
                                ->label(__('Customer email'))
                                ->required()
                                ->searchable()
                                ->preload()
                                ->afterStateUpdated(function (mixed $state, Set $set): void {
                                    $user_id = $state;

                                    if ($user_id === null) {
                                        $set('shipping_address_id', '');
                                        $set('billing_address_id', '');
                                    }
                                })
                                ->live(onBlur: true)
                                ->hintAction(
                                    Action::make(__('Open user'))
                                        ->icon('heroicon-o-user-group')
                                        ->url(
                                            function (Get $get): string {
                                                $user_id = $get('user_id');

                                                return $user_id !== null ? route('filament.admin.resources.users.users.edit', $user_id) : route('filament.admin.resources.users.users.index');
                                            },
                                            shouldOpenInNewTab: true
                                        )
                                ),
                            Select::make('shipping_address_id')
                                ->relationship('shippingAddress', 'address')
                                ->disabled(fn(Get $get): bool => blank($get('user_id')))
                                ->options(
                                    fn(Get $get): ?array => self::getAddressId($get)
                                )
                                ->selectablePlaceholder(function (Get $get): bool {
                                    $user_id = $get('user_id');
                                    $order_id = $get('id');

                                    return match (true) {
                                        $order_id !== null => false,
                                        $user_id === null => true,
                                        default => true,
                                    };
                                })
                                ->columnSpanFull()
                                ->label(__('Shipping address'))
                                ->required(),
                            Select::make('billing_address_id')
                                ->relationship('billingAddress', 'address')
                                ->disabled(fn(Get $get): bool => blank($get('user_id')))
                                ->options(
                                    fn(Get $get): ?array => self::getAddressId($get)
                                )
                                ->selectablePlaceholder(function (Get $get): bool {
                                    $user_id = $get('user_id');
                                    $order_id = $get('id');

                                    return match (true) {
                                        $order_id !== null => false,
                                        $user_id === null => true,
                                        default => true,
                                    };
                                })
                                ->columnSpanFull()
                                ->required()
                                ->label(__('Billing address')),
                        ]),
                ])->columns(2),

                Section::make([
                    static::getProductsRepeater(),
                ]),

                Section::make(__('Payment'))
                    ->schema([
                        TextInput::make('purchase_cost')
                            ->label(__('Price with taxes'))
                            ->required()
                            ->numeric()
                            ->dehydrated()
                            ->disabled(),

                        TextInput::make('discount')
                            ->label(__('Discount (in percentage %)'))
                            ->hint(__('Will be applied after taxes'))
                            ->default(0)
                            ->minValue(0)
                            ->numeric()
                            ->decimalPlaces(2)
                            ->lazy()
                            ->afterStateUpdated(
                                function (Get $get, Set $set): void {
                                    self::updateTotals($get, $set);
                                }
                            ),

                        ToggleButtons::make('payment_method')
                            ->label(__('Payment method'))
                            ->inline()
                            ->options(PaymentMethod::class)
                            ->default(PaymentMethod::Card)
                            ->required()
                            ->columnSpan('full'),

                        ToggleButtons::make('status')
                            ->label(__('Status'))
                            ->inline()
                            ->options(OrderStatus::class)
                            ->default(OrderStatus::PaymentPending)
                            ->required()
                            ->columnSpan('full'),

                    ])->columns(2),
            ])
            ->live();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ExportAction::make()
                    ->exporter(OrderExporter::class),
            ])
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label(__('User'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('purchase_cost')
                    ->label(__('Purchase cost'))
                    ->money(
                        currency: 'eur',
                        locale: 'es'
                    )
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->sortable()
                    ->badge(),
                TextColumn::make('payment_method')
                    ->label(__('Payment method'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->date()
                    ->label(__('Order date')),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
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

    public static function getProductsRepeater(): Repeater
    {
        return Repeater::make('orderProducts')
            ->label(__('Order products'))
            ->relationship()
            ->schema([
                Hidden::make('orderable_type')
                    // ->options([
                    //    ProductSparePart::class => 'Repuesto',
                    // ])
                    // ->live()
                    // ->afterStateUpdated(function (Set $set) {
                    //    $set('orderable_id', '');
                    // })
                    ->default(ProductSparePart::class)
                    ->required()
                    ->columnSpan([
                        'md' => 5,
                    ]),

                Select::make('orderable_id')
                    ->label(__('Product'))
                    ->disabled(fn(Get $get): bool => blank($get('orderable_type')))
                    ->options(function (Get $get) {
                        if (blank($get('orderable_type'))) {
                            return;
                        }

                        /** @var BaseProduct */
                        $class_name = $get('orderable_type');

                        return $class_name::query()->pluck('name', 'id')->toArray();
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->distinct()
                    ->afterStateUpdated(function (mixed $state, Get $get, Set $set): void {
                        /** @var BaseProduct */
                        $class_name = $get('orderable_type');

                        /** @var ?BaseProduct */
                        $product = $class_name::query()->find($state);

                        if (! $product) {
                            $set('unit_price', 0);
                            $set('retailer_price', 0);
                            $set('quantity', 1);

                            return;
                        }

                        $set('unit_price', $product->price_with_discount);
                        $set('retailer_price', $product->price);
                    })
                    ->columnSpanFull(),

                TextInput::make('quantity')
                    ->label(__('Quantity'))
                    ->numeric()
                    ->integer()
                    ->default(1)
                    ->minValue(1)
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->required(),

                TextInput::make('unit_price')
                    ->label(__('Unit price'))
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->required()
                    ->suffix('€')
                    ->columnSpan([
                        'md' => 3,
                    ]),

                TextInput::make('retailer_price')
                    ->label(__('Price to retailer'))
                    ->disabled()
                    ->numeric()
                    ->suffix('€')
                    ->columnSpan([
                        'md' => 3,
                    ]),
            ])
            ->columns([
                'md' => 10,
            ])
            // @see: https://laraveldaily.com/post/filament-repeater-live-calculations-on-update
            ->live()
            ->afterStateUpdated(
                function (Get $get, Set $set): void {
                    self::updateTotals($get, $set);
                }
            )
            ->deleteAction(
                function (Action $action): void {
                    $action->after(
                        function (Get $get, Set $set): void {
                            self::updateTotals($get, $set);
                        }
                    );
                }
            );
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

    /**
     * @return array<int|string, string>|null
     */
    public static function getAddressId(Get $get): ?array
    {
        /** @var ?int */
        $user_id = $get('user_id');

        /** @var ?int */
        $order_id = $get('id');

        if ($user_id === null && $order_id !== null) {
            $address = Order::query()
                ->find((int) $order_id)
                ?->shippingAddress
                ?->address;

            return $address !== null ? [0 => (string) $address] : [];
        }

        if ($user_id === null) {
            /** @var array<int, string> */
            return Address::query()
                ->select('address')
                ->pluck('address')
                ->toArray();
        }

        /** @var array<int|string, string>|null */
        $ret = User::query()
            ->find((int) $user_id)
            ?->shippingAddresses
            ->pluck('address', 'id')
            ->toArray() ?? [];

        return $ret;
    }

    public static function updateTotals(Get $get, Set $set): void
    {
        /** @var array<int, array<string, mixed>> $raw_products */
        $raw_products = $get('orderProducts') ?? [];

        $order_products = collect();

        foreach ($raw_products as $selected_product) {
            // Skip empty rows or rows missing quantity
            if (! isset(
                $selected_product['orderable_type'],
                $selected_product['orderable_id'],
                $selected_product['quantity']
            )) {
                continue;
            }

            if (! is_string($selected_product['orderable_type'])) {
                continue;
            }

            if (! class_exists($selected_product['orderable_type'])) {
                continue;
            }

            /** @var ?BaseProduct */
            $product = $selected_product['orderable_type']::find($selected_product['orderable_id']);

            if (! $product) {
                continue;
            }

            /** @var string */
            $quantity = $selected_product['quantity'];

            $order_products->add(new OrderProductDTO(
                $product->id,
                $selected_product['orderable_type'],
                $product->price_with_discount ?: $product->price,
                (int) $quantity,
                $product
            ));
        }

        /** @var float */
        $discount = $get('discount') ?? 0;

        $price_calculator = new PriceCalculator();
        $set('purchase_cost', $price_calculator->getTotalCostForOrderWithTaxesAndManualDiscount(
            order_products: $order_products,
            apply_discount: true,
            percentage_discount: $discount
        ));
    }
}
