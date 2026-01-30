<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Notifications\OrderConfirmationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use LogicException;

class SendOrderConfirmationToUser implements ShouldQueue
{
    public function handle(OrderCreated $event): void
    {
        $user = $event->order->user;

        throw_if($user === null, LogicException::class, 'Cannot send order confirmation without a user.');

        $user->notify(new OrderConfirmationNotification($event->order));
    }
}
