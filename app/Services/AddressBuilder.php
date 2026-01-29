<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AddressType;
use App\Enums\PaymentMethod;
use App\Enums\Role;
use App\Models\Address;
use App\Models\User;
use Exception;
use Filament\Schemas\Schema;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AddressBuilder
{
    private ?User $user;

    private Address $shipping_address;

    private Address $billing_address;

    private readonly string $shipping_name;

    private readonly string $shipping_surname;

    private readonly string $shipping_email;

    private readonly string $shipping_business_name;

    private readonly string $shipping_cif;

    private readonly int $shipping_phone;

    private readonly string $shipping_address_str;

    private readonly string $shipping_city;

    private readonly string $shipping_state;

    private readonly int $shipping_zip_code;

    private readonly string $shipping_country;

    private readonly string $billing_name;

    private readonly string $billing_surname;

    private readonly string $billing_cif;

    private readonly string $billing_business_name;

    private readonly int $billing_phone;

    private readonly string $billing_address_str;

    private readonly string $billing_city;

    private readonly string $billing_state;

    private readonly int $billing_zip_code;

    private readonly string $billing_country;

    private readonly int $shipping_address_id;

    private readonly int $billing_address_id;

    private readonly string $order_details;

    private readonly bool $purchase_as_guest;

    private readonly bool $use_shipping_address_as_billing_address;

    // Payment method
    private PaymentMethod $payment_method = PaymentMethod::BankTransfer;

    public function __construct(Schema $schema)
    {
        $form_data = $schema->getState();
        $this->user = Auth::user();

        // Shipping values
        $this->shipping_name = $this->get_string($form_data, 'shipping_name');
        $this->shipping_surname = $this->get_string($form_data, 'shipping_surname');
        $this->shipping_email = $this->get_string($form_data, 'shipping_email');
        $this->shipping_business_name = $this->get_string($form_data, 'shipping_business_name');
        $this->shipping_cif = $this->get_string($form_data, 'shipping_cif');
        $this->shipping_phone = $this->get_int($form_data, 'shipping_phone');
        $this->shipping_address_str = $this->get_string($form_data, 'shipping_address');
        $this->shipping_city = $this->get_string($form_data, 'shipping_city');
        $this->shipping_state = $this->get_string($form_data, 'shipping_state');
        $this->shipping_zip_code = $this->get_int($form_data, 'shipping_zip_code');
        $this->shipping_country = $this->get_string($form_data, 'shipping_country');

        // Billing values
        $this->billing_name = $this->get_string($form_data, 'billing_name');
        $this->billing_surname = $this->get_string($form_data, 'billing_surname');
        $this->billing_business_name = $this->get_string($form_data, 'billing_business_name');
        $this->billing_cif = $this->get_string($form_data, 'billing_cif');
        $this->billing_phone = $this->get_int($form_data, 'billing_phone');
        $this->billing_address_str = $this->get_string($form_data, 'billing_address_str');
        $this->billing_city = $this->get_string($form_data, 'billing_city');
        $this->billing_state = $this->get_string($form_data, 'billing_state');
        $this->billing_zip_code = $this->get_int($form_data, 'billing_zip_code');
        $this->billing_country = $this->get_string($form_data, 'billing_country');

        // Shipping for authenticated user when selects a previous one
        $this->shipping_address_id = $this->get_int($form_data, 'shipping_address_id');

        // Billing for authenticated user when selects a previous one
        $this->billing_address_id = $this->get_int($form_data, 'billing_address_id');

        // Same billing and shipping address
        $this->use_shipping_address_as_billing_address = (bool) (data_get($form_data, 'use_shipping_address_as_billing_address'));

        // User does not wants to register
        $this->purchase_as_guest = (bool) (data_get($form_data, 'purchase_as_guest'));

        // Comments for the order
        $this->order_details = $this->get_string($form_data, 'order_details');
    }

    public function paymentMethod(): PaymentMethod
    {
        return $this->payment_method;
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function shippingAddress(): Address
    {
        return $this->shipping_address;
    }

    public function billingAddress(): Address
    {
        return $this->billing_address;
    }

    public function orderDetails(): string
    {
        return $this->order_details;
    }

    /**
     * @throws UniqueConstraintViolationException
     */
    public function build(): void
    {
        if (! $this->user instanceof User) {
            $this->buildNotRegisteredUserOrder();
        } else {
            $this->buildRegisteredUserOrder();
        }
    }

    private function buildRegisteredUserOrder(): void
    {
        // shipping_address_id is 0 when user selects "New address"
        if ($this->shipping_address_id === 0) {
            $this->buildShippingAddressFromUserInput();
        } else {
            $this->buildShippingAddressFromId();
        }

        // If user uses same shipping and billing adddress
        if ($this->use_shipping_address_as_billing_address) {
            $this->billing_address = $this->shipping_address;
        } elseif ($this->billing_address_id !== 0) {
            // If user selects some other registered billing address
            // If user has selected one registered address
            $this->buildBillingAddressFromId();
        } else {
            $this->buildBillingAddressFromUserInput();
        }
    }

    /**
     * @throws UniqueConstraintViolationException
     */
    private function buildNotRegisteredUserOrder(): void
    {
        if ($this->purchase_as_guest === false) {
            $this->createUserAccount();
        }

        $this->buildShippingAddressFromUserInput();

        // If user uses same shipping and billing adddress
        if ($this->use_shipping_address_as_billing_address) {
            $this->billing_address = $this->shipping_address;
        } else {
            $this->buildBillingAddressFromUserInput();
        }
    }

    /**
     * @throws UniqueConstraintViolationException
     */
    private function createUserAccount(): void
    {
        $this->user = User::query()->create([
            'name' => $this->shipping_name,
            'surname' => $this->shipping_surname,
            'email' => $this->shipping_email,
            'password' => Str::password(),
            'role' => Role::Customer,
        ]);
    }

    private function buildShippingAddressFromId(): void
    {
        $this->shipping_address = $this->buildAddressFromId($this->shipping_address_id);
    }

    private function buildBillingAddressFromId(): void
    {
        $this->billing_address = $this->buildAddressFromId($this->billing_address_id);
    }

    private function buildAddressFromId(int $address_id): Address
    {
        $address = Address::query()->find($address_id);

        throw_if($address === null, Exception::class, 'Cannot find address', 1);

        throw_unless($this->validateAddressBelongsToUser($address), Exception::class, 'Address does not belongs to user', 1);

        return $address;
    }

    private function buildShippingAddressFromUserInput(): void
    {
        // If not set email value (user is registered but selects new address), we get user email
        $email = $this->shipping_email !== '' ? $this->shipping_email : $this->user?->email;

        // If user is registered, we associate the address to the user
        $user_id = $this->user instanceof User ? $this->user->id : null;

        $this->shipping_address = Address::query()->create([
            'user_id' => $user_id,
            'email' => $email,
            'address_type' => AddressType::Shipping,
            'name' => $this->shipping_name,
            'surname' => $this->shipping_surname,
            'vat_number' => $this->shipping_cif,
            'business_name' => $this->shipping_business_name,
            'phone' => $this->shipping_phone,
            'address' => $this->shipping_address_str,
            'city' => $this->shipping_city,
            'state' => $this->shipping_state,
            'zip_code' => $this->shipping_zip_code,
            'country' => $this->shipping_country,
        ]);
    }

    private function buildBillingAddressFromUserInput(): void
    {
        // We do not allow users to use two different emails
        $email = $this->shipping_address->email;

        // If user is registered, we associate the address to the user
        $user_id = $this->user instanceof User ? $this->user->id : null;

        $this->billing_address = Address::query()->create([
            'user_id' => $user_id,
            'email' => $email,
            'address_type' => AddressType::Shipping,
            'name' => $this->billing_name,
            'surname' => $this->billing_surname,
            'business_name' => $this->billing_business_name,
            'vat_number' => $this->billing_cif,
            'phone' => $this->billing_phone,
            'address' => $this->billing_address_str,
            'city' => $this->billing_city,
            'state' => $this->billing_state,
            'zip_code' => $this->billing_zip_code,
            'country' => $this->billing_country,
        ]);
    }

    private function validateAddressBelongsToUser(Address $address): bool
    {
        if (is_null($this->user)) {
            return false;
        }

        return $this->user->addresses->pluck('id')->contains($address->id);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function get_string(array $data, string $key, string $default = ''): string
    {
        $value = data_get($data, $key);

        return is_scalar($value) || $value === null ? (string) $value : $default;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function get_int(array $data, string $key, int $default = 0): int
    {
        $value = data_get($data, $key);

        return is_scalar($value) || $value === null ? (int) $value : $default;
    }
}
