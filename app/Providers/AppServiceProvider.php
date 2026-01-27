<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Responses\FilamentLoginResponse;
use App\Http\Responses\FilamentLogoutResponse;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponse::class, FilamentLogoutResponse::class);
        $this->app->bind(LoginResponse::class, FilamentLoginResponse::class);
    }

    /**
     * This are security measures
     */
    public function boot(): void
    {
        DB::prohibitDestructiveCommands(app()->isProduction());

        // As these are concerned with application correctness,
        // leave them enabled all the time.
        Model::preventAccessingMissingAttributes();
        Model::preventSilentlyDiscardingAttributes();

        // Since this is a performance concern only, don’t halt
        // production for violations.
        Model::preventLazyLoading(! app()->isProduction());

        // Log a warning if we spend more than a total of 2000ms querying.
        DB::whenQueryingForLongerThan(2000, function (Connection $connection): void {
            Log::warning("Database queries exceeded 2 seconds on {$connection->getName()}");
        });

        // Log a warning if we spend more than 1000ms on a single query.
        DB::listen(function ($query): void {
            if ($query->time > 1000) {
                Log::warning('An individual database query exceeded 1 second.', [
                    'sql' => $query->sql,
                ]);
            }
        });

        if ($this->app->runningInConsole()) {
            // Log slow commands.
            $this->app->make(ConsoleKernel::class)->whenCommandLifecycleIsLongerThan(
                5000,
                function ($startedAt, $input, $status): void {
                    Log::warning('A command took longer than 5 seconds.');
                }
            );
        } else {
            // Log slow requests.
            $this->app->make(HttpKernel::class)->whenRequestLifecycleIsLongerThan(
                5000,
                function ($startedAt, $request, $response): void {
                    Log::warning('A request took longer than 5 seconds.');
                }
            );
        }
    }
}
