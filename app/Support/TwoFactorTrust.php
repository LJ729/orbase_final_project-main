<?php

namespace App\Support;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Throwable;

class TwoFactorTrust
{
    public const COOKIE_NAME = 'trusted_two_factor_login';

    public const DURATION_MINUTES = 120;

    /**
     * Build an encrypted cookie payload for the trusted 2FA window.
     */
    public static function cookieValue(Authenticatable $user): string
    {
        return Crypt::encryptString(json_encode([
            'user_id' => $user->getAuthIdentifier(),
            'secret_hash' => sha1((string) $user->two_factor_secret),
        ]));
    }

    /**
     * Determine if the request can skip the 2FA challenge.
     */
    public static function isTrusted(Request $request, mixed $user): bool
    {
        if (! $user ||
            ! in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user)) ||
            empty($user->two_factor_secret)) {
            return false;
        }

        $cookie = $request->cookie(self::COOKIE_NAME);

        if (! is_string($cookie) || $cookie === '') {
            return false;
        }

        try {
            $payload = json_decode(Crypt::decryptString($cookie), true, flags: JSON_THROW_ON_ERROR);
        } catch (Throwable) {
            return false;
        }

        return ($payload['user_id'] ?? null) == $user->getAuthIdentifier()
            && ($payload['secret_hash'] ?? null) === sha1((string) $user->two_factor_secret);
    }
}
