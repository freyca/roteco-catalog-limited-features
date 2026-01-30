<?php

declare(strict_types=1);

use App\Enums\PaymentMethod;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Payment\Traits\PaymentActions;
use App\Services\Payment;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

describe('Payment Service', function (): void {
    beforeEach(function (): void {
        test()->admin = User::factory()->admin_notifiable()->create();
        test()->order = Order::factory()->create([
            'payment_method' => PaymentMethod::BankTransfer,
        ]);
        test()->service = new Payment(test()->order);
    });

    it('calls payPurchase and isGatewayOkWithPayment on BankTransferPaymentRepository', function (): void {
        $service = test()->service;
        $response = $service->payPurchase();
        expect($response)->not->toBeNull();
        expect($response->getStatusCode())->toBeIn([301, 302]);
        $location = $response->headers->get('Location');
        expect($location)->toBeString();
        $route = resolve(Router::class)->getRoutes()->match(Request::create($location));
        $routeName = $route->getName();
        expect($routeName)->not->toBeNull();
        expect($routeName)->toBeIn(['payment.purchase-complete', 'payment.purchase-failed', 'pago-completo', 'pago-fallido']);

        $gatewayOk = $service->isGatewayOkWithPayment(request());
        expect(is_bool($gatewayOk))->toBeTrue(); // Should always be boolean
    });
});

describe('PaymentActions trait', function (): void {
    beforeEach(function (): void {
        test()->trait = new class
        {
            use PaymentActions;

            public function callConvertPriceToCents(float $price): int
            {
                return $this->convertPriceToCents($price);
            }
        };
    });

    it('convertPriceToCents converts float price to cents', function (): void {
        $trait = test()->trait;
        expect($trait->callConvertPriceToCents(12.34))->toBe(1234);
        expect($trait->callConvertPriceToCents(0.99))->toBe(99);
        expect($trait->callConvertPriceToCents(100.00))->toBe(10000);
    });
});
