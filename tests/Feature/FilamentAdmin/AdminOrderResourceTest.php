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

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    test()->admin = User::factory()->admin_notifiable()->create();

    Filament::setCurrentPanel(
        Filament::getPanel('admin')
    );
});

describe('AdminOrderResource', function (): void {
    it('admin can access order list page', function (): void {
        test()->actingAs(test()->admin);

        Livewire::test(ListOrders::class)
            ->assertStatus(200);
    });

    it('can display orders in list table', function (): void {
        test()->actingAs(test()->admin);
        $orders = Order::factory(3)->create();

        $component = Livewire::test(ListOrders::class);

        foreach ($orders as $order) {
            $component->assertSee($order->id);
        }

        expect($orders)->toHaveCount(3);
    });

    it('admin can access edit order page', function (): void {
        test()->actingAs(test()->admin);
        $order = Order::factory()->create();

        Livewire::test(EditOrder::class, ['record' => $order->getRouteKey()])
            ->assertStatus(200);
    });

    it('order resource is read-only (no create page)', function (): void {
        $pages = OrderResource::getPages();
        expect($pages)->toHaveKey('index');
        expect($pages)->toHaveKey('create');
    });

    it('order resource has index page', function (): void {
        $pages = OrderResource::getPages();
        expect($pages)->toHaveKey('index');
    });

    it('order resource has edit page', function (): void {
        $pages = OrderResource::getPages();
        expect($pages)->toHaveKey('edit');
    });

    it('order resource has correct navigation group', function (): void {
        $group = OrderResource::getNavigationGroup();
        expect($group)->toBe(__('Usuarios'));
    });

    it('order resource has correct model label', function (): void {
        $label = OrderResource::getModelLabel();
        expect($label)->toBe(__('Pedidos'));
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

        test()->actingAs(test()->admin);
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
        $user = User::factory()->admin()->create();
        $address = Address::factory(['address_type' => AddressType::ShippingAndBilling])->for($user)->create();

        $product = ProductSparePart::factory()->create([
            'price' => 100,
            'price_with_discount' => 90,
        ]);

        actingAs($user);

        $undoRepeaterFake = Repeater::fake();

        Livewire::test(CreateOrder::class)
            ->fillForm([
                'user_id' => $user->id,
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
        expect($order->user_id)->toBe($user->id);
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
        // Create user & address
        $user = User::factory()->admin()->create();
        $address = Address::factory(['address_type' => AddressType::ShippingAndBilling])
            ->for($user)
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
        $order = Order::factory()->for($user)->create([
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

        actingAs($user);

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
        expect($order->user_id)->toBe($user->id);

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
});
