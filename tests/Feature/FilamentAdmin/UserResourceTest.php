<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Filament\Admin\Resources\Users\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Users\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Users\Users\Pages\ListUsers;
use App\Filament\Admin\Resources\Users\Users\UserResource;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function (): void {
    test()->admin = User::factory()->admin_notifiable()->create();

    Filament::setCurrentPanel(
        Filament::getPanel('admin')
    );
});

describe('UserResource', function (): void {
    it('admin can access user list page', function (): void {
        test()->actingAs(test()->admin);
        $component = Livewire::test(ListUsers::class);
        $component->assertSee(__('User'));
    });

    it('can display users in list table', function (): void {
        test()->actingAs(test()->admin);
        $users = User::factory(3)->create();

        $component = Livewire::test(ListUsers::class);

        foreach ($users as $user) {
            $component->assertSee($user->email);
        }
    });

    it('admin can access create user page', function (): void {
        test()->actingAs(test()->admin);
        $component = Livewire::test(CreateUser::class);
        $component->assertFormComponentExists('name');
    });

    it('can create a new user via form', function (): void {
        test()->actingAs(test()->admin);
        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'New User',
                'surname' => 'Test Surname',
                'email' => 'newuser@test.com',
                'password' => 'password123',
                'role' => Role::Customer->value,
            ])
            ->call('create')
            ->assertHasNoFormErrors();
        expect(User::query()->where('email', 'newuser@test.com')->exists())->toBeTrue();
    });

    it('can create an admin user via form', function (): void {
        test()->actingAs(test()->admin);
        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Admin User',
                'surname' => 'Admin Surname',
                'email' => 'adminuser@test.com',
                'password' => 'adminpassword',
                'role' => Role::Admin->value,
            ])
            ->call('create')
            ->assertHasNoFormErrors();
        expect(User::query()->where('email', 'adminuser@test.com')->where('role', Role::Admin->value)->exists())->toBeTrue();
    });

    it('validates name is required on create', function (): void {
        test()->actingAs(test()->admin);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => '',
                'surname' => 'Test Surname',
                'email' => 'test@test.com',
                'password' => 'password123',
                'role' => Role::Customer->value,
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required']);
    });

    it('validates email is required on create', function (): void {
        test()->actingAs(test()->admin);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Test User',
                'surname' => 'Test Surname',
                'email' => '',
                'password' => 'password123',
                'role' => Role::Customer->value,
            ])
            ->call('create')
            ->assertHasFormErrors(['email' => 'required']);
    });

    it('validates surname is required on create', function (): void {
        test()->actingAs(test()->admin);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Test User',
                'surname' => '',
                'email' => 'test@test.com',
                'password' => 'password123',
                'role' => Role::Customer->value,
            ])
            ->call('create')
            ->assertHasFormErrors(['surname' => 'required']);
    });

    it('can create user without password', function (): void {
        test()->actingAs(test()->admin);
        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Test User',
                'surname' => 'Test Surname',
                'email' => 'test@test.com',
                'password' => '',
                'role' => Role::Customer->value,
            ])
            ->call('create')
            ->assertHasNoFormErrors();
        expect(User::query()->where('email', 'test@test.com')->exists())->toBeTrue();
    });

    it('admin can access edit user page', function (): void {
        test()->actingAs(test()->admin);
        $user = User::factory()->create();

        Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
            ->assertStatus(200);
    });

    it('can update user via form', function (): void {
        test()->actingAs(test()->admin);
        $user = User::factory()->create(['name' => 'Old Name']);
        Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
            ->fillForm([
                'name' => 'Updated Name',
            ])
            ->call('save');
        expect(User::query()->find($user->id)->name)->toBe('Updated Name');
    });

    it('validates name is required on update', function (): void {
        test()->actingAs(test()->admin);
        $user = User::factory()->create();
        Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
            ->fillForm([
                'name' => '',
            ])
            ->call('save')
            ->assertHasFormErrors(['name' => 'required']);
    });

    it('user resource has correct navigation group', function (): void {
        $group = UserResource::getNavigationGroup();
        expect($group)->toBe(__('Users'));
    });

    it('user resource has correct model label', function (): void {
        $label = UserResource::getModelLabel();
        expect($label)->toBe(__('User'));
    });

    it('resource has index page', function (): void {
        $pages = UserResource::getPages();
        expect($pages)->toHaveKey('index');
    });

    it('resource has create page', function (): void {
        $pages = UserResource::getPages();
        expect($pages)->toHaveKey('create');
    });

    it('resource has edit page', function (): void {
        $pages = UserResource::getPages();
        expect($pages)->toHaveKey('edit');
    });
});
