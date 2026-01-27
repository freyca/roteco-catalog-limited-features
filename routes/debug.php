<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\User;
use App\Notifications\AdminOrderNotification;
use App\Notifications\OrderConfirmationNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Route;

if (app()->hasDebugModeEnabled()) {
    Route::get('/preview/user-notification', function (): MailMessage {
        $order = Order::factory()->create();
        $notification = new OrderConfirmationNotification($order);

        return $notification->toMail(User::factory()->create());
    });

    Route::get('/preview/admin-notification', function (): MailMessage {
        $order = Order::factory()->create();
        $notification = new AdminOrderNotification($order);

        return $notification->toMail(User::factory()->create());
    });
}
