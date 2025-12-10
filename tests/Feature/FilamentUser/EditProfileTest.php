<?php

use App\Enums\Role;
use App\Filament\User\Pages\Auth\EditProfile;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    test()->user = User::factory()->create(['role' => Role::Customer]);

    Filament::setCurrentPanel(
        Filament::getPanel('user')
    );
});

describe('EditProfile Page', function () {
    it('user can access edit profile page', function () {
        test()->actingAs(test()->user);
        $component = Livewire::test(EditProfile::class);
        $component->assertFormComponentExists('name');
    });

    it('user can update profile name', function () {
        test()->actingAs(test()->user);
        $component = Livewire::test(EditProfile::class);
        $component->fillForm([
            'name' => 'Updated Name',
            'surname' => test()->user->surname,
            'email' => test()->user->email,
        ])->call('save');
        expect(test()->user->fresh()->name)->toBe('Updated Name');
    });

    it('user can update profile surname', function () {
        test()->actingAs(test()->user);

        $component = Livewire::test(EditProfile::class);
        $component->fillForm([
            'name' => test()->user->name,
            'surname' => 'Updated Surname',
            'email' => test()->user->email,
        ])->call('save');
        expect(test()->user->fresh()->surname)->toBe('Updated Surname');
    });

    it('user can update profile email', function () {
        test()->actingAs(test()->user);

        $component = Livewire::test(EditProfile::class);
        $component->fillForm([
            'name' => test()->user->name,
            'surname' => test()->user->surname,
            'email' => 'newemail@example.com',
        ])->call('save');
        expect(test()->user->fresh()->email)->toBe('newemail@example.com');
    });

    it('user can update profile password', function () {
        test()->actingAs(test()->user);
        $oldPassword = test()->user->password;

        $component = Livewire::test(EditProfile::class);
        $component->fillForm([
            'name' => test()->user->name,
            'surname' => test()->user->surname,
            'email' => test()->user->email,
            'password' => 'newpassword123',
            'passwordConfirmation' => 'newpassword123',
        ])->call('save');
        expect(test()->user->fresh()->password)->not()->toBe($oldPassword);
    });

    it('validates name is required', function () {
        test()->actingAs(test()->user);

        $component = Livewire::test(EditProfile::class);
        $component->fillForm([
            'name' => '',
            'surname' => test()->user->surname,
            'email' => test()->user->email,
        ])->call('save');
        $component->assertHasFormErrors(['name' => 'required']);
    });

    it('validates surname is required', function () {
        test()->actingAs(test()->user);

        $component = Livewire::test(EditProfile::class);
        $component->fillForm([
            'name' => test()->user->name,
            'surname' => '',
            'email' => test()->user->email,
        ])->call('save');
        $component->assertHasFormErrors(['surname' => 'required']);
    });

    it('validates email is required', function () {
        test()->actingAs(test()->user);

        $component = Livewire::test(EditProfile::class);
        $component->fillForm([
            'name' => test()->user->name,
            'surname' => test()->user->surname,
            'email' => '',
        ])->call('save');
        $component->assertHasFormErrors(['email' => 'required']);
    });

    it('validates email format', function () {
        test()->actingAs(test()->user);

        Livewire::test(EditProfile::class)
            ->fillForm([
                'name' => test()->user->name,
                'surname' => test()->user->surname,
                'email' => 'not-an-email',
            ])
            ->call('save')
            ->assertHasFormErrors(['email']);
    });

    it('password and confirmation can be different when no password change', function () {
        test()->actingAs(test()->user);

        Livewire::test(EditProfile::class)
            ->fillForm([
                'name' => test()->user->name,
                'surname' => test()->user->surname,
                'email' => test()->user->email,
                'password' => '',
                'passwordConfirmation' => '',
            ])
            ->call('save')
            ->assertHasNoFormErrors();
    });

    it('form has name component', function () {
        test()->actingAs(test()->user);

        Livewire::test(EditProfile::class)
            ->assertFormComponentExists('name');
    });

    it('form has surname component', function () {
        test()->actingAs(test()->user);

        Livewire::test(EditProfile::class)
            ->assertFormComponentExists('surname');
    });

    it('form has email component', function () {
        test()->actingAs(test()->user);

        Livewire::test(EditProfile::class)
            ->assertFormComponentExists('email');
    });

    it('form has password component', function () {
        test()->actingAs(test()->user);

        Livewire::test(EditProfile::class)
            ->assertFormComponentExists('password');
    });

    it('form has password confirmation component', function () {
        test()->actingAs(test()->user);

        Livewire::test(EditProfile::class)
            ->assertFormComponentExists('passwordConfirmation');
    });
});
