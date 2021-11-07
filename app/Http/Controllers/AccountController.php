<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function create(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return Inertia::render(
            'Dashboard/Account',
            [
                'totp' => [
                    'enabled' => !!$user->two_factor_secret,
                    'svg' => $user->two_factor_secret && !$user->two_factor_verified ? $request->user()->twoFactorQrCodeSvg() : null,
                    'verified' => !!$user->two_factor_verified
                ]
            ]
        );
    }

    /**
     * @throws ValidationException
     */
    public function verifyTOTP(Request $request)
    {
        $request->validate([
            'code' => 'required|string|digits:6',
        ]);

        /** @var User $user */
        $user = $request->user();

        if (!$user->verifyTOTP($request->code)) {
            throw ValidationException::withMessages([
                'code' => __('auth.totp-code-verification'),
            ]);
        }

        return back();
    }

    public function downloadRecoveryCodes(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        if (!$user->hasTOTPEnabled()) {
            abort(500);
        }

        return response()->streamDownload(function () use ($user) {
            echo json_encode($user->recoveryCodes());
        }, 'recovery-codes.txt');
    }

    public function store(Request $request, UpdateUserProfileInformation $updateUserProfileInformation, UpdateUserPassword $updateUserPassword)
    {
        /** @var User $user */
        $user = $request->user();

        $updateUserProfileInformation->update($user, $request->toArray());

        if ($request->password) {
            $updateUserPassword->update($user, $request->toArray());
        }

        $user->save();

        return back();
    }
}
