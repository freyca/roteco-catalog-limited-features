<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Disassembly;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class DisassemblyList extends Component
{
    /**
     * @param  Collection<int, Disassembly>  $relatedDisassemblies
     */
    public function __construct(public Collection $relatedDisassemblies) {}

    public function render(): View|Closure|string
    {
        return view('components.disassembly-list');
    }
}
