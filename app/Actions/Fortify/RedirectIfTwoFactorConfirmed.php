<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

// cred @ Nicolas Bailly
// https://dev.to/nicolus/laravel-fortify-implement-2fa-in-a-way-that-won-t-let-users-lock-themselves-out-2ejk
class RedirectIfTwoFactorConfirmed extends RedirectIfTwoFactorAuthenticatable
{
    public function handle($request, $next)
    {
        $user = $this->validateCredentials($request);

        if (optional($user)->two_factor_verified &&
            in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user))) {
            return $this->twoFactorChallengeResponse($request, $user);
        }

        return $next($request);
    }
}
