<?php

namespace App\Http\Responses;

use App\Support\TwoFactorTrust;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Laravel\Fortify\Fortify;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    /**
     * Create an HTTP response and remember the successful 2FA verification for 2 hours.
     */
    public function toResponse($request)
    {
        $response = $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect()->intended(Fortify::redirects('login'));

        return $response->cookie(cookie()->make(
            TwoFactorTrust::COOKIE_NAME,
            TwoFactorTrust::cookieValue($request->user()),
            TwoFactorTrust::DURATION_MINUTES,
            secure: $request->isSecure(),
            httpOnly: true,
            sameSite: 'lax',
        ));
    }
}