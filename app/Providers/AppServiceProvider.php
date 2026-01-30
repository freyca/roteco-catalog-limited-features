<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Responses\FilamentLoginResponse;
use App\Http\Responses\FilamentLogoutResponse;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LogoutResponse::class, FilamentLogoutResponse::class);
        $this->app->bind(LoginResponse::class, FilamentLoginResponse::class);
    }

    public function boot(): void {}
}
