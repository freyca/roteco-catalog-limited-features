<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LogoutResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FilamentLogoutResponse implements LogoutResponse
{
    /**
     * @param  Request  $request
     */
    public function toResponse($request): RedirectResponse  // @pest-ignore-type
    {
        return to_route('home');
    }
}
