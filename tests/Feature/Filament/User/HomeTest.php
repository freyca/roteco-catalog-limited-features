<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Filament\User\Pages\Home;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function (): void {
    test()->password = '123123123';
    test()->user = User::factory()->create([
        'role' => Role::Customer,
        'password' => test()->password,
    ]);

    test()->actingAs(test()->user);
});

describe('Access filament user panel', function (): void {
    it('user gets redirected to orders after login', function (): void {
        Livewire::test(Home::class)
            ->assertRedirect(
                route('filament.user.resources.orders.index')
            );
    });
});
