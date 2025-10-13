<?php

namespace App\Filament\Support\Traits;

use Illuminate\Support\Facades\Auth;

trait RedirectIfNotAuthenticated
{
    protected function ensureAuthenticated()
    {
        if (!Auth::check()) {
            $currentUrl = request()->fullUrl();

            return redirect()->route('shop.login', [
                'redirect_to' => $currentUrl
            ]);
        }

        return null; // means user is authenticated
    }
}
