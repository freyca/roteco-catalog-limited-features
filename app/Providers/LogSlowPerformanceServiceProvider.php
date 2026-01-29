<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Database\Connection;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class LogSlowPerformanceServiceProvider extends ServiceProvider
{
    /**
     * @codeCoverageIgnore
     */
    public function boot(): void
    {
        // Log a warning if we spend more than a total of 2000ms querying.
        DB::whenQueryingForLongerThan(2000, function (Connection $connection): void {
            Log::warning("Database queries exceeded 2 seconds on {$connection->getName()}");
        });

        // Log a warning if we spend more than 1000ms on a single query.
        DB::listen(function (QueryExecuted $query): void {
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
                function (mixed $startedAt, mixed $input, mixed $status): void {
                    Log::warning('A command took longer than 5 seconds.');
                }
            );
        } else {
            // Log slow requests.
            $this->app->make(HttpKernel::class)->whenRequestLifecycleIsLongerThan(
                5000,
                function (mixed $startedAt, mixed $request, mixed $response): void {
                    Log::warning('A request took longer than 5 seconds.');
                }
            );
        }
    }
}
