<?php

namespace App\Actions\Fortify;

use App\Support\TwoFactorTrust;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\TwoFactorAuthenticatable;

class RedirectIfTrustedTwoFactorAuthenticatable extends RedirectIfTwoFactorAuthenticatable
{
    /**
     * Redirect users to the 2FA challenge unless their trusted cookie is still valid.
     */
    public function handle($request, $next)
    {
        $user = $this->validateCredentials($request);

        if (Fortify::confirmsTwoFactorAuthentication()) {
            if (optional($user)->two_factor_secret &&
                ! is_null(optional($user)->two_factor_confirmed_at) &&
                in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user))) {
                if (TwoFactorTrust::isTrusted($request, $user)) {
                    return $next($request);
                }

                return $this->twoFactorChallengeResponse($request, $user);
            }

            return $next($request);
        }

        if (optional($user)->two_factor_secret &&
            in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user))) {
            if (TwoFactorTrust::isTrusted($request, $user)) {
                return $next($request);
            }

            return $this->twoFactorChallengeResponse($request, $user);
        }

        return $next($request);
    }
}
