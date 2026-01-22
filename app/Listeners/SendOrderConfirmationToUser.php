<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Notifications\OrderConfirmationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderConfirmationToUser implements ShouldQueue
{
    public function handle(OrderCreated $event): void
    {
        $user = $event->order->user;

        if ($user === null) {
            throw new \LogicException('Cannot send order confirmation without a user.');
        }

        $user->notify(new OrderConfirmationNotification($event->order));
    }
}
