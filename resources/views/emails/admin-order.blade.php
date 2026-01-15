@component('mail::message')
# {{ __('New Order Created') }}

## {{ __('Order Details') }}

- **{{ __('Order ID') }}:** {{ $order->id }}
- **{{ __('Customer') }}:** {{ $order->user->name }} {{ $order->user->surname }}
- **{{ __('Customer Email') }}:** {{ $order->user->email }}
- **{{ __('Order Status') }}:** {{ $order->status->getLabel() }}
- **{{ __('Total Amount') }}:** {{ $order->formatCurrency($order->purchase_cost) }}
- **{{ __('Payment Method') }}:** {{ $order->payment_method->value }}

----
<br>

@if (!$order->billingAddress || $order->billingAddress->id === $order->shippingAddress->id)
## {{ __('Addresses')  }}
{{ __('Shipping and Billing') }}
@else
## {{ __('Shipping Address') }}
@endif

- **{{ __('Name') }}**: {{ $order->shippingAddress->name }} {{ $order->shippingAddress->surname }}
- **{{ __('Address') }}**: {{ $order->shippingAddress->address }}
- **{{ __('Zip code') }}**: {{ $order->shippingAddress->zip_code }} {{ $order->shippingAddress->city }}
- **{{ __('State') . ' ' . __('and') . ' ' . __('Country') }}**: {{ $order->shippingAddress->state }}, {{ $order->shippingAddress->country }}

@if ($order->billingAddress && $order->billingAddress->id !== $order->shippingAddress->id)
## {{ __('Billing Address') }}

- **{{ __('Name') }}**: {{ $order->billingAddress->name }} {{ $order->billingAddress->surname }}
- **{{ __('Address') }}**: {{ $order->billingAddress->address }}
- **{{ __('Zip code') }}**: {{ $order->billingAddress->zip_code }} {{ $order->billingAddress->city }}
- **{{ __('State') . ' ' . __('and') . ' ' . __('Country') }}**: {{ $order->billingAddress->state }}, {{ $order->billingAddress->country }}
@endif

---
<br>

## {{ __('Products') }}

| {{ __('Product') }} | {{ __('Quantity') }} | {{ __('Unit Price') }} | {{ __('Total') }} |
|---------|----------|---------|-------|
@foreach ($products as $product)
| {{ $product->orderable->name }} | {{ $product->quantity }} | {{ $product->unit_price }}€ | {{ $product->orderable->getFormattedPrice() }} |
@endforeach

@endcomponent
