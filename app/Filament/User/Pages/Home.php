<?php

declare(strict_types=1);

namespace App\Filament\User\Pages;

use Filament\Pages\Dashboard;

class Home extends Dashboard
{
    protected static ?string $slug = null;

    protected static bool $shouldRegisterNavigation = false;

    public function mount(): void
    {
        $this->redirect(route('filament.user.resources.orders.index'));
    }
}
