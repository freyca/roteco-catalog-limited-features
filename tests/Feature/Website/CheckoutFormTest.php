<?php

declare(strict_types=1);

use App\Enums\AddressType;
use App\Models\Address;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function (): void {
    test()->admin = User::factory()->admin_notifiable()->create();
    test()->user = User::factory()->create();

    test()->actingAs(test()->user);
});

test('user can complete checkout filling the form', function (): void {
    Livewire::test('forms.checkout-form')
        ->set('checkoutFormData.shipping_name', test()->user->name)
        ->set('checkoutFormData.shipping_surname', test()->user->surname)
        ->set('checkoutFormData.shipping_email', test()->user->email)
        ->set('checkoutFormData.shipping_phone', '1234567890')
        ->set('checkoutFormData.shipping_address', '123 Test Street')
        ->set('checkoutFormData.shipping_city', 'Test City')
        ->set('checkoutFormData.shipping_state', 'Test State')
        ->set('checkoutFormData.shipping_zip_code', '12345')
        ->set('checkoutFormData.shipping_country', 'ES')
        ->set('checkoutFormData.use_shipping_address_as_billing_address', false)
        ->set('checkoutFormData.billing_name', test()->user->name)
        ->set('checkoutFormData.billing_surname', test()->user->surname)
        ->set('checkoutFormData.billing_phone', '1234567890')
        ->set('checkoutFormData.billing_address', '123 Test Street')
        ->set('checkoutFormData.billing_city', 'Test City')
        ->set('checkoutFormData.billing_state', 'Test State')
        ->set('checkoutFormData.billing_zip_code', '12345')
        ->set('checkoutFormData.billing_country', 'ES')
        ->call('create')
        ->assertHasNoFormErrors();
});

test('user can complete checkout filling the form only for shipping address and using billing as same', function (): void {
    Livewire::test('forms.checkout-form')
        ->set('checkoutFormData.shipping_name', test()->user->name)
        ->set('checkoutFormData.shipping_surname', test()->user->surname)
        ->set('checkoutFormData.shipping_email', test()->user->email)
        ->set('checkoutFormData.shipping_phone', '1234567890')
        ->set('checkoutFormData.shipping_address', '123 Test Street')
        ->set('checkoutFormData.shipping_city', 'Test City')
        ->set('checkoutFormData.shipping_state', 'Test State')
        ->set('checkoutFormData.shipping_zip_code', '12345')
        ->set('checkoutFormData.shipping_country', 'ES')
        ->set('checkoutFormData.use_shipping_address_as_billing_address', true)
        ->call('create')
        ->assertHasNoFormErrors();
});

test('user can complete checkout using same saved addresses', function (): void {
    $address = Address::factory()->for(test()->user)->create(['address_type' => AddressType::ShippingAndBilling]);

    Livewire::test('forms.checkout-form')
        ->set('checkoutFormData.shipping_address_id', $address->id)
        ->set('checkoutFormData.use_shipping_address_as_billing_address', true)
        ->call('create')
        ->assertHasNoFormErrors();
});

test('user can complete checkout using different saved addresses', function (): void {
    $shipping_address = Address::factory()->for(test()->user)->create(['address_type' => AddressType::Shipping]);
    $billing_address = Address::factory()->for(test()->user)->create(['address_type' => AddressType::Billing]);

    Livewire::test('forms.checkout-form')
        ->set('checkoutFormData.shipping_address_id', $shipping_address->id)
        ->set('checkoutFormData.use_shipping_address_as_billing_address', false)
        ->set('checkoutFormData.billing_address_id', $billing_address->id)
        ->call('create')
        ->assertHasNoFormErrors();
});
