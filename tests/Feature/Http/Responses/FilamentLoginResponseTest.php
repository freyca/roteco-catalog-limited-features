<?php

declare(strict_types=1);

use App\Http\Responses\FilamentLoginResponse;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

beforeEach(function (): void {
    test()->admin = User::factory()->admin_notifiable()->create();
    test()->customer = User::factory()->customer()->create();
});

describe('FilamentLoginResponse', function (): void {
    it('redirects non logged in user to login page', function (): void {
        $response = new FilamentLoginResponse;
        $request = Request::create('/admin', 'GET');

        $result = $response->toResponse($request);

        expect($result)->toBeInstanceOf(RedirectResponse::class);
        expect($result->getTargetUrl())->toBe(url('/'));
    });

    it('redirects admin to admin panel', function (): void {
        test()->actingAs(test()->admin);
        $response = new FilamentLoginResponse;
        $request = Request::create('/login', 'POST');

        $result = $response->toResponse($request);

        expect($result)->toBeInstanceOf(RedirectResponse::class);
        expect($result->getTargetUrl())->toBe(url('/admin'));
    });

    it('redirects customer to home', function (): void {
        test()->actingAs(test()->customer);
        $response = new FilamentLoginResponse;
        $request = Request::create('/login', 'POST');

        $result = $response->toResponse($request);

        expect($result)->toBeInstanceOf(RedirectResponse::class);
        expect($result->getTargetUrl())->toBe(url('/'));
    });
});
