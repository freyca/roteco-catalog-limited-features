<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Filament\Admin\Widgets\OrderStatsOverview;
use App\Models\Order;
use App\Models\User;

beforeEach(function (): void {
    test()->admin = User::factory()->admin_notifiable()->create();
});

describe('OrderStatsOverview Widget', function (): void {
    it('calculates total orders count', function (): void {
        Order::factory(5)->create();

        $widget = new OrderStatsOverview;
        $reflection = new ReflectionMethod($widget, 'getStats');
        $stats = $reflection->invoke($widget);

        expect($stats[0]->getValue())->toBe(5);
    });

    it('calculates total revenue', function (): void {
        Order::factory()->create(['purchase_cost' => 10000]);
        Order::factory()->create(['purchase_cost' => 5000]);

        $widget = new OrderStatsOverview;
        $reflection = new ReflectionMethod($widget, 'getStats');
        $stats = $reflection->invoke($widget);

        expect($stats[1]->getValue())->toContain('€15');
    });

    it('counts pending orders', function (): void {
        Order::factory(3)->create(['status' => OrderStatus::PaymentPending]);
        Order::factory(2)->create(['status' => OrderStatus::Delivered]);

        $widget = new OrderStatsOverview;
        $reflection = new ReflectionMethod($widget, 'getStats');
        $stats = $reflection->invoke($widget);

        expect($stats[2]->getValue())->toBe(3);
    });

    it('counts completed orders', function (): void {
        Order::factory(2)->create(['status' => OrderStatus::PaymentPending]);
        Order::factory(4)->create(['status' => OrderStatus::Delivered]);

        $widget = new OrderStatsOverview;
        $reflection = new ReflectionMethod($widget, 'getStats');
        $stats = $reflection->invoke($widget);

        expect($stats[3]->getValue())->toBe(4);
    });

    it('returns four stats', function (): void {
        $widget = new OrderStatsOverview;
        $reflection = new ReflectionMethod($widget, 'getStats');
        $stats = $reflection->invoke($widget);

        expect($stats)->toHaveCount(4);
    });

    it('first stat is total orders', function (): void {
        $widget = new OrderStatsOverview;
        $reflection = new ReflectionMethod($widget, 'getStats');
        $stats = $reflection->invoke($widget);

        expect($stats[0]->getLabel())->toBe(__('Total Orders'));
    });

    it('second stat is total revenue', function (): void {
        $widget = new OrderStatsOverview;
        $reflection = new ReflectionMethod($widget, 'getStats');
        $stats = $reflection->invoke($widget);

        expect($stats[1]->getLabel())->toBe(__('Total Revenue'));
    });

    it('third stat is pending orders', function (): void {
        $widget = new OrderStatsOverview;
        $reflection = new ReflectionMethod($widget, 'getStats');
        $stats = $reflection->invoke($widget);

        expect($stats[2]->getLabel())->toBe(__('Pending Orders'));
    });

    it('fourth stat is completed orders', function (): void {
        $widget = new OrderStatsOverview;
        $reflection = new ReflectionMethod($widget, 'getStats');
        $stats = $reflection->invoke($widget);

        expect($stats[3]->getLabel())->toBe(__('Completed Orders'));
    });

    it('stats have descriptions', function (): void {
        $widget = new OrderStatsOverview;
        $reflection = new ReflectionMethod($widget, 'getStats');
        $stats = $reflection->invoke($widget);

        foreach ($stats as $stat) {
            expect($stat->getDescription())->not()->toBeNull();
        }
    });

    it('stats have colors', function (): void {
        $widget = new OrderStatsOverview;
        $reflection = new ReflectionMethod($widget, 'getStats');
        $stats = $reflection->invoke($widget);

        foreach ($stats as $stat) {
            expect($stat->getColor())->not()->toBeNull();
        }
    });
});
