<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\User;
use App\Notifications\AdminOrderNotification;
use App\Notifications\OrderConfirmationNotification;
use Illuminate\Support\Facades\Route;

if (app()->hasDebugModeEnabled()) {
    Route::get('/preview/user-notification', function () {
        $order = Order::first();
        $notification = new OrderConfirmationNotification($order);

        return $notification->toMail(User::first());
    });

    Route::get('/preview/admin-notification', function () {
        $order = Order::first();
        $notification = new AdminOrderNotification($order);

        return $notification->toMail(User::first());
    });
}
