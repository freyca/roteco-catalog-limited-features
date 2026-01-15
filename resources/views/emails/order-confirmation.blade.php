@component('mail::message')
# {{ __('Order Confirmation') }}
<br>
{{ __('Hello') }}, {{ $order->user->name }},

{{ __('Thank you for your order!') }}

## {{ __('Order Details') }}
- **{{ __('Order ID') }}:** {{ $order->id }}
- **{{ __('Order Status') }}:** {{ $order->status->getLabel() }}
- **{{ __('Total Amount') }}:** {{ $order->formatCurrency($order->purchase_cost) }}
- **{{ __('Payment Method') }}:** {{ $order->payment_method->value }}

<br>

## {{ __('Shipping Address') }}
- **{{ __('Name') }}**: {{ $order->shippingAddress->name }} {{ $order->shippingAddress->surname }}
- **{{ __('Address') }}**: {{ $order->shippingAddress->address }}
- **{{ __('Zip code') }}**: {{ $order->shippingAddress->zip_code }} {{ $order->shippingAddress->city }}
- **{{ __('State') . ' ' . __('and') . ' ' . __('Country') }}**: {{ $order->shippingAddress->state }}, {{ $order->shippingAddress->country }}

<br>

## {{ __('Products') }}

| {{ __('Product') }} | {{ __('Quantity') }} | {{ __('Price') }} |
|-------------------|------------------|-----------------|
@foreach ($products as $product)
| {{ $product->orderable->name }} | {{ $product->quantity }} | {{ $product->orderable->getFormattedPrice() }} |
@endforeach

<br>

{{ __('If you have any questions, please contact us.') }}

{{ __('Best regards') }},
@endcomponent
