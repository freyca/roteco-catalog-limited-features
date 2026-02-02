<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Orders\Schemas;

use App\DTO\OrderProductDTO;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\Address;
use App\Models\BaseProduct;
use App\Models\Order;
use App\Models\ProductSparePart;
use App\Models\User;
use App\Services\PriceCalculator;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextInput::make('id')
                        ->name(__('Order id (automatically generated)').':')
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
                                ->disabled(fn (Get $get): bool => blank($get('user_id')))
                                ->options(
                                    fn (Get $get): array => self::getAddressIds(get: $get, type: 'shipping')
                                )
                                ->selectablePlaceholder(function (Get $get): bool {
                                    $user_id = $get('user_id');
                                    $order_id = $get('id');

                                    return match (true) {
                                        $order_id !== null => false,
                                        $user_id === null => true,
                                        default => false,
                                    };
                                })
                                ->columnSpanFull()
                                ->label(__('Shipping address'))
                                ->required(),

                            Select::make('billing_address_id')
                                ->relationship('billingAddress', 'address')
                                ->disabled(fn (Get $get): bool => blank($get('user_id')))
                                ->options(
                                    fn (Get $get): array => self::getAddressIds(get: $get, type: 'billing')
                                )
                                ->selectablePlaceholder(function (Get $get): bool {
                                    $user_id = $get('user_id');
                                    $order_id = $get('id');

                                    return match (true) {
                                        $order_id !== null => false,
                                        $user_id === null => true,
                                        default => false,
                                    };
                                })
                                ->columnSpanFull()
                                ->required()
                                ->label(__('Billing address')),
                        ]),
                ])->columns(2),

                Section::make([
                    self::getProductsRepeater(),
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
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
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

        $set(
            'purchase_cost',
            new PriceCalculator()->getTotalCostForOrderWithTaxesAndManualDiscount(
                order_products: $order_products,
                apply_discount: true,
                percentage_discount: $discount
            )
        );
    }

    private static function getProductsRepeater(): Repeater
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
                    ->disabled(fn (Get $get): bool => blank($get('orderable_type')))
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

    /**
     * @return array<int, string>
     */
    private static function getAddressIds(Get $get, string $type): array
    {
        /** @var ?int */
        $user_id = $get('user_id');

        /** @var ?string */
        $order_id = $get('id');

        if ($user_id === null) {
            return [];
        }

        $addresses = collect();

        if ($order_id !== null) {
            /** @var Order */
            $order = Order::query()->find($order_id);

            $relationMethod = $type.'Address';

            /**
             * Billing address could be null, in that case we take shipping
             * address as billing address, which can't be null
             *
             * @var Address $orderAddress
             */
            $orderAddress = $order->$relationMethod ?? $order->shippingAddress;

            $addresses->put(
                $orderAddress->id,
                $orderAddress->address
            );
        }

        /** @var User */
        $user = User::query()->find((int) $user_id);

        $relation_method = $type.'Addresses';

        /** @var HasMany<Address, User> $relation */
        $relation = $user->$relation_method();

        /** @var Collection<int, string> */
        $user_addresses = $relation->pluck('address', 'id');

        /** @var array<int, string> $result */
        $result = $addresses
            ->merge($user_addresses)
            ->unique()
            ->all();

        return $result;
    }
}
