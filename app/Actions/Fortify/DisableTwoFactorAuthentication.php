<?php

namespace App\Actions\Fortify;

// cred @ Nicolas Bailly
// https://dev.to/nicolus/laravel-fortify-implement-2fa-in-a-way-that-won-t-let-users-lock-themselves-out-2ejk
class DisableTwoFactorAuthentication extends \Laravel\Fortify\Actions\DisableTwoFactorAuthentication
{
    public function __invoke($user)
    {
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_verified' => null,
        ])->save();
    }
}
