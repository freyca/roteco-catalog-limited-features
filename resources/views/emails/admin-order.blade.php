@component('mail::message')
# {{ __('New Order Created') }}

## {{ __('Order Details') }}

- **{{ __('Order ID') }}:** {{ $order->id }}
- **{{ __('Customer') }}:** {{ $order->user->name }} {{ $order->user->surname }}
- **{{ __('Customer Email') }}:** {{ $order->user->email }}
- **{{ __('Order Status') }}:** {{ $order->status->getLabel() }}
- **{{ __('Total Amount') }}:** €{{ number_format($order->purchase_cost / 100, 2) }}
- **{{ __('Payment Method') }}:** {{ $order->payment_method->value }}

## {{ __('Products') }}

| {{ __('Product') }} | {{ __('Quantity') }} | {{ __('Unit Price') }} | {{ __('Total') }} |
|---------|----------|---------|-------|
@foreach ($products as $product)
| {{ $product->orderable->name }} | {{ $product->quantity }} | {{ $product->unit_price }}€ | {{ number_format(($product->unit_price * $product->quantity), 2) }}€ |
@endforeach


## {{ __('Shipping Address') }}

{{ $order->shippingAddress->name }} {{ $order->shippingAddress->surname }} <br/>
{{ $order->shippingAddress->address }} <br/>
{{ $order->shippingAddress->zip_code }} {{ $order->shippingAddress->city }} <br/>
{{ $order->shippingAddress->state }}, {{ $order->shippingAddress->country }} <br/>

@if ($order->billingAddress && $order->billingAddress->id !== $order->shippingAddress->id)
## {{ __('Billing Address') }}

{{ $order->billingAddress->name }} {{ $order->billingAddress->surname }} <br/>
{{ $order->billingAddress->address }} <br/>
{{ $order->billingAddress->zip_code }} {{ $order->billingAddress->city }} <br/>
{{ $order->billingAddress->state }}, {{ $order->billingAddress->country }} <br/>
@endif

---

{{ __('Best regards') }},
@endcomponent
