<?php

declare(strict_types=1);

use App\DTO\OrderProductDTO;
use App\Enums\AddressType;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Filament\Admin\Resources\Users\Orders\OrderResource;
use App\Filament\Admin\Resources\Users\Orders\Pages\CreateOrder;
use App\Filament\Admin\Resources\Users\Orders\Pages\EditOrder;
use App\Filament\Admin\Resources\Users\Orders\Pages\ListOrders;
use App\Models\Address;
use App\Models\Order;
use App\Models\ProductSparePart;
use App\Models\User;
use App\Services\PriceCalculator;
use Filament\Facades\Filament;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function (): void {
    test()->admin = User::factory()->admin_notifiable()->create();
    test()->actingAs(test()->admin);

    Filament::setCurrentPanel(
        Filament::getPanel('admin')
    );
});

describe('AdminOrderResource', function (): void {
    it('admin can access order list page', function (): void {
        Livewire::test(ListOrders::class)
            ->assertStatus(200);
    });

    it('can display orders in list table', function (): void {
        $orders = Order::factory(3)->create();

        $component = Livewire::test(ListOrders::class);

        foreach ($orders as $order) {
            $component->assertSee($order->id);
        }

        expect($orders)->toHaveCount(3);
    });

    it('admin can access edit order page', function (): void {
        $order = Order::factory()->create();

        Livewire::test(EditOrder::class, ['record' => $order->getRouteKey()])
            ->assertStatus(200)
            ->assertHasNoFormErrors();
    });

    it('order resource has option', function (string $key): void {
        expect(OrderResource::getPages())->toHaveKey($key);
    })
        ->with([
            'index',
            'create',
            'edit',
        ]);

    it('order resource doesnt has option', function (string $key): void {
        expect(OrderResource::getPages())->not->toHaveKey($key);
    })
        ->with([
            'delete',
        ]);

    it('order resource has correct navigation group', function (): void {
        expect(OrderResource::getNavigationGroup())->toBe(__('Usuarios'));
    });

    it('order resource has correct model label', function (): void {
        expect(OrderResource::getModelLabel())->toBe(__('Pedidos'));
    });

    it('can export orders via table action', function (): void {
        // Clean up export files before test
        $exportDirs = Storage::disk('local')->directories('filament_exports');
        foreach ($exportDirs as $dir) {
            $files = Storage::disk('local')->files($dir);
            foreach ($files as $file) {
                Storage::disk('local')->delete($file);
            }
        }

        Order::factory(3)->create();

        // Test the export action through Livewire
        Livewire::test(ListOrders::class)
            ->mountTableAction('export')
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors();

        // Find all CSV files except headers and xlsx
        $exportDirs = Storage::disk('local')->directories('filament_exports');
        $dataCsvFiles = [];
        foreach ($exportDirs as $dir) {
            $files = Storage::disk('local')->files($dir);
            foreach ($files as $file) {
                if (str_ends_with($file, '.csv') && ! str_contains($file, 'headers')) {
                    $dataCsvFiles[] = $file;
                }
            }
        }
        expect($dataCsvFiles)->not->toBeEmpty();
        $csv = Storage::disk('local')->get($dataCsvFiles[0]);
        // Check CSV contains at least one order code
        $order = Order::query()->first();
        expect($csv)->toContain($order->id);

        // Clean up export files after test
        foreach ($dataCsvFiles as $file) {
            Storage::disk('local')->delete($file);
        }
    });

    it('can create an order', function (): void {
        $address = Address::factory(['address_type' => AddressType::ShippingAndBilling])->for(test()->admin)->create();

        $product = ProductSparePart::factory()->create([
            'price' => 100,
            'price_with_discount' => 90,
        ]);

        $undoRepeaterFake = Repeater::fake();

        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(CreateOrder::class)
            ->fillForm([
                'user_id' => test()->admin->id,
                'shipping_address_id' => $address->id,
                'billing_address_id' => $address->id,
                'discount' => 10,
                'payment_method' => PaymentMethod::Card,
                'status' => OrderStatus::Paid,
                'orderProducts' => [
                    0 => [
                        'orderable_type' => ProductSparePart::class,
                        'orderable_id' => $product->id,
                        'quantity' => 2,
                        'unit_price' => $product->price_with_discount,
                    ],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $undoRepeaterFake();

        $order = Order::query()->first();

        // Validate order belongs to user
        expect($order->user_id)->toBe(test()->admin->id);
        expect($order->shipping_address_id)->toBe($address->id);
        expect($order->billing_address_id)->toBe($address->id);

        // Validate products in order
        $orderProduct = $order->orderProducts()->first();
        expect($orderProduct->orderable_type)->toBe(ProductSparePart::class);
        expect($orderProduct->orderable)->toBeInstanceOf(ProductSparePart::class);
        expect($orderProduct->orderable->id)->toBe($product->id);
        expect($orderProduct->unit_price)->toBe($product->price_with_discount);
        expect($orderProduct->quantity)->toBe(2);

        // Validate prices in order
        $priceCalculator = new PriceCalculator();
        $expectedTotal = $priceCalculator->getTotalCostForOrderWithTaxesAndManualDiscount(
            order_products: collect([
                new OrderProductDTO(
                    $product->id,
                    ProductSparePart::class,
                    $product->price_with_discount,
                    2,
                    $product
                ),
            ]),
            apply_discount: true,
            percentage_discount: 10.0
        );

        expect($order->purchase_cost)->toBe($expectedTotal);
    });

    it('can update an order adding a product', function (): void {
        $address = Address::factory(['address_type' => AddressType::ShippingAndBilling])
            ->for(test()->admin)
            ->create();

        // Create products
        $product1 = ProductSparePart::factory()->create([
            'price' => 100,
            'price_with_discount' => 90,
        ]);
        $product2 = ProductSparePart::factory()->create([
            'price' => 200,
            'price_with_discount' => 180,
        ]);

        // Create initial order
        $order = Order::factory()->for(test()->admin)->create([
            'shipping_address_id' => $address->id,
            'billing_address_id' => $address->id,
            'discount' => 0,
            'payment_method' => PaymentMethod::Card,
            'status' => OrderStatus::Paid,
        ]);

        $order->orderProducts()->create([
            'orderable_type' => ProductSparePart::class,
            'orderable_id' => $product1->id,
            'quantity' => 1,
            'unit_price' => $product1->price_with_discount,
        ]);

        // Fake repeater UUIDs
        $undoRepeaterFake = Repeater::fake();

        // Form data for update
        $updateData = [
            'discount' => 20,
            'status' => OrderStatus::Shipped,
            'orderProducts' => [
                0 => [
                    'orderable_type' => ProductSparePart::class,
                    'orderable_id' => $product2->id,
                    'quantity' => 3,
                    'unit_price' => $product2->price_with_discount,
                ],
            ],
        ];

        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(EditOrder::class, [
            'record' => $order->getKey(),
        ])
            ->fillForm($updateData)
            ->call('save')
            ->assertHasNoFormErrors();

        $undoRepeaterFake();

        // Refresh order & fetch products (only product2 should exist)
        $order->refresh();
        $products = $order->orderProducts;

        // Assert old product is still present
        expect($products->pluck('orderable_id'))->toContain($product1->id);

        // Assert only product2 exists
        expect($products->pluck('orderable_id'))->toContain($product2->id);

        // Assert there are two products
        expect($products->count())->toBe(2);

        // Assert order fields
        expect($order->discount)->toBe(20.0);
        expect($order->status)->toBe(OrderStatus::Shipped);
        expect($order->shipping_address_id)->toBe($address->id);
        expect($order->billing_address_id)->toBe($address->id);
        expect($order->user_id)->toBe(test()->admin->id);

        $order->load('orderProducts.orderable');
        $products = $order->orderProducts;
        $orderProduct = $products->where('orderable_id', $product2->id)->first();

        // Assert product
        expect($orderProduct->orderable_type)->toBe(ProductSparePart::class);
        expect($orderProduct->orderable)->toBeInstanceOf(ProductSparePart::class);
        expect($orderProduct->orderable_id)->toBe($product2->id);
        expect($orderProduct->quantity)->toBe(3);
        expect($orderProduct->unit_price)->toBe($product2->price_with_discount);

        // Assert recalculated purchase_cost
        $expectedTotal = new PriceCalculator()->getTotalCostForOrderWithTaxesAndManualDiscount(
            order_products: collect([
                new OrderProductDTO(
                    $product2->id,
                    ProductSparePart::class,
                    $product2->price_with_discount,
                    3,
                    $product2
                ),
                new OrderProductDTO(
                    $product1->id,
                    ProductSparePart::class,
                    $product1->price_with_discount,
                    1,
                    $product1
                ),
            ]),
            apply_discount: true,
            percentage_discount: 20.0
        );

        expect($order->purchase_cost)->toBe($expectedTotal);
    });

    it('resets addresses when user_id is null', function (): void {
        Livewire::test(CreateOrder::class)
            ->fillForm([
                'user_id' => test()->admin->id,
            ])
            ->fillForm([
                'user_id' => null,
            ])
            ->assertSchemaStateSet([
                'shipping_address_id' => '',
                'billing_address_id' => '',
            ]);
    });
});
