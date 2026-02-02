<?php

declare(strict_types=1);

namespace App\Filament\User\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\ProductSparePart;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    Select::make('payment_method')
                        ->label(__('Payment method'))
                        ->options(PaymentMethod::class),
                    TextInput::make('purchase_cost')
                        ->label(__('Price'))
                        ->suffix('€')
                        ->numeric(),
                    ToggleButtons::make('status')
                        ->label(__('Status'))
                        ->inline()
                        ->options(OrderStatus::class)
                        ->columnSpan('full'),
                ])->columns(2),

                Section::make([
                    static::getProductsRepeater(),
                ]),
            ]);
    }

    public static function getProductsRepeater(): Repeater
    {
        return Repeater::make('orderProducts')
            ->label(__('Products'))
            ->relationship()
            ->schema([
                Select::make('orderable_id')
                    ->label(__('Product'))
                    ->options(ProductSparePart::query()->pluck('name', 'id'))
                    ->columnSpan([
                        'md' => 5,
                    ]),

                TextInput::make('quantity')
                    ->label(__('Quantity'))
                    ->numeric()
                    ->columnSpan([
                        'md' => 2,
                    ]),

                TextInput::make('unit_price')
                    ->label(__('Unit price'))
                    ->numeric()
                    ->columnSpan([
                        'md' => 3,
                    ]),
            ])
            ->columns([
                'md' => 10,
            ]);
    }
}
