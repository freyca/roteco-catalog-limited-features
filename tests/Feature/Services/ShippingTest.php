<?php

declare(strict_types=1);

use App\Repositories\Shipping\ShippingRepositoryInterface;
use App\Services\Shipping;

beforeEach(function (): void {
    test()->repository = mock(ShippingRepositoryInterface::class);
    test()->shipping = new Shipping(test()->repository);
});

describe('Shipping Service', function (): void {
    it('gets track status', function (): void {
        test()->repository
            ->shouldReceive('getTrackStatus')
            ->once()
            ->andReturn('In Transit');

        $status = test()->shipping->getTrackStatus();

        expect($status)->toBe('In Transit');
    });

    it('gets track status as delivered', function (): void {
        test()->repository
            ->shouldReceive('getTrackStatus')
            ->once()
            ->andReturn('Delivered');

        $status = test()->shipping->getTrackStatus();

        expect($status)->toBe('Delivered');
    });

    it('checks if shipment is shipped', function (): void {
        test()->repository
            ->shouldReceive('isShipped')
            ->once()
            ->andReturn(true);

        $isShipped = test()->shipping->isShipped();

        expect($isShipped)->toBeTrue();
    });

    it('checks if shipment is not shipped', function (): void {
        test()->repository
            ->shouldReceive('isShipped')
            ->once()
            ->andReturn(false);

        $isShipped = test()->shipping->isShipped();

        expect($isShipped)->toBeFalse();
    });

    it('gets track information url', function (): void {
        $url = 'https://tracking.courier.com/track/ABC123';

        test()->repository
            ->shouldReceive('getTrackInformationUrl')
            ->once()
            ->andReturn($url);

        $trackUrl = test()->shipping->getTrackInformationUrl();

        expect($trackUrl)->toBe($url);
    });
});
