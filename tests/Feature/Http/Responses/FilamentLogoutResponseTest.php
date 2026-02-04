<?php

declare(strict_types=1);

use App\Http\Responses\FilamentLogoutResponse;
use Illuminate\Http\Request;

describe('FilamentLogoutResponse', function (): void {
    it('redirects to home route', function (): void {
        $response = new FilamentLogoutResponse;
        $request = Request::create('/admin', 'GET');

        $result = $response->toResponse($request);

        expect($result->getTargetUrl())->toBe(url('/'))
            ->and($result->getStatusCode())->toBe(302);
    });

    it('logout response redirects to named route', function (): void {
        $response = new FilamentLogoutResponse;
        $request = Request::create('/admin', 'GET');

        $result = $response->toResponse($request);
        $url = $result->getTargetUrl();

        expect($url)->toBe(route('home'));
    });
});
