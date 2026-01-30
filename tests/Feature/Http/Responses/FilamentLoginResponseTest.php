<?php

declare(strict_types=1);

use App\Http\Responses\FilamentLoginResponse;
use App\Models\User;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

beforeEach(function (): void {
    test()->admin = User::factory()->admin_notifiable()->create();
    test()->customer = User::factory()->customer()->create();
});

describe('FilamentLoginResponse', function (): void {
    it('is instance of LoginResponse contract', function (): void {
        $response = new FilamentLoginResponse;

        expect($response)->toBeInstanceOf(LoginResponse::class);
    });

    it('redirects admin to admin panel', function (): void {
        test()->actingAs(test()->admin);
        $response = new FilamentLoginResponse;
        $request = Request::create('/login', 'POST');

        $result = $response->toResponse($request);

        expect($result->getTargetUrl())->toContain('/admin');
    });

    it('redirects customer to home', function (): void {
        test()->actingAs(test()->customer);
        $response = new FilamentLoginResponse;
        $request = Request::create('/login', 'POST');

        $result = $response->toResponse($request);

        expect($result->getTargetUrl())->not()->toContain('/admin');
    });

    it('returns redirect response', function (): void {
        test()->actingAs(test()->customer);
        $response = new FilamentLoginResponse;
        $request = Request::create('/login', 'POST');

        $result = $response->toResponse($request);

        expect($result)->toBeInstanceOf(RedirectResponse::class);
    });

    it('redirects correctly for admin user', function (): void {
        auth()->login(test()->admin);
        $response = new FilamentLoginResponse;
        $request = Request::create('/login', 'POST');

        $result = $response->toResponse($request);

        expect($result->getTargetUrl())->toContain('/admin');
    });

    it('redirects correctly for customer user', function (): void {
        auth()->login(test()->customer);
        $response = new FilamentLoginResponse;
        $request = Request::create('/login', 'POST');

        $result = $response->toResponse($request);

        expect($result->getTargetUrl())->not()->toContain('/admin');
    });
});
