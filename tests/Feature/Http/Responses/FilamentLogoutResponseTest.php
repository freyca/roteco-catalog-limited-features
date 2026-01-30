<?php

declare(strict_types=1);

use App\Http\Responses\FilamentLogoutResponse;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

describe('FilamentLogoutResponse', function (): void {
    it('redirects to home route', function (): void {
        $response = new FilamentLogoutResponse;
        $request = Request::create('/admin', 'GET');

        $result = $response->toResponse($request);

        expect($result->getTargetUrl())->toContain('/')
            ->and($result->getStatusCode())->toBe(302);
    });

    it('is instance of LogoutResponse contract', function (): void {
        $response = new FilamentLogoutResponse;

        expect($response)->toBeInstanceOf(LogoutResponse::class);
    });

    it('returns redirect response', function (): void {
        $response = new FilamentLogoutResponse;
        $request = Request::create('/admin', 'GET');

        $result = $response->toResponse($request);

        expect($result)->toBeInstanceOf(RedirectResponse::class);
    });

    it('logout response redirects to named route', function (): void {
        $response = new FilamentLogoutResponse;
        $request = Request::create('/admin', 'GET');

        $result = $response->toResponse($request);
        $url = $result->getTargetUrl();

        expect($url)->toBe(route('home'));
    });
});
