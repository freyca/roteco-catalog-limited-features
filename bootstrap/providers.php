<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FilamentCustomizationProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\UserPanelProvider::class,
    App\Providers\LogSlowPerformanceServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class,
];
