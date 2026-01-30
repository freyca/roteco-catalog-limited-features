<?php

declare(strict_types=1);

namespace App\Providers;

use Filament\Notifications\Livewire\Notifications;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\ServiceProvider;

class FilamentCustomizationProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Filament colors
        FilamentColor::register([
            'primary' => Color::Zinc,
            'info' => Color::Blue,
            'warning' => Color::Yellow,
            'success' => Color::Green,
            'danger' => Color::Red,
        ]);

        // Notifications on left so does not overlap cart icon
        Notifications::alignment(Alignment::Start);

        Fieldset::configureUsing(fn (Fieldset $fieldset): Fieldset => $fieldset
            ->columnSpanFull());

        Grid::configureUsing(fn (Grid $grid): Grid => $grid
            ->columnSpanFull());

        Section::configureUsing(fn (Section $section): Section => $section
            ->columnSpanFull());
    }
}
