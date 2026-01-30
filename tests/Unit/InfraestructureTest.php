<?php

declare(strict_types=1);

use Illuminate\Http\Request;

test('Ensures custom form request used in controllers', function (): void {

    expect(Request::class)
        ->not
        ->toBeUsedIn('App\Http\Controllers');
});

test('Ensures that no debugging commands are present in the code ready to commit', function (): void {

    expect(['dd', 'dump', 'ray', 'var_dump', 'info', 'error_log'])
        ->not
        ->toBeUsed();
});

test('Ensures env command not used outside config files', function (): void {

    expect(['env'])
        ->not
        ->toBeUsed();
});

test('Ensures that strict_types is declared in all classes', function (): void {

    expect('App')
        ->toUseStrictTypes();
});

test('Controllers must have Controller sufix', function (): void {

    expect('App\Http\Controllers')
        ->toHaveSuffix('Controller');
});
