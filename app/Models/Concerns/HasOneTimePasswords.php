<?php

namespace App\Models\Concerns;

use App\Models\OneTimePassword;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

trait HasOneTimePasswords
{
    public function oneTimePasswords(): MorphMany
    {
        return $this->morphMany(OneTimePassword::class, 'authenticatable');
    }

    /**
     * Create a new one-time password and return the plain code.
     * The code is stored hashed in the database.
     */
    public function createOneTimePassword(?int $expiresInMinutes = null): string
    {
        $length = (int) config('one-time-passwords.password_length', 6);
        $expiresInMinutes ??= (int) config('one-time-passwords.default_expires_in_minutes', 2);

        $code = str_pad((string) random_int(0, (10 ** $length) - 1), $length, '0', STR_PAD_LEFT);

        if (config('one-time-passwords.only_one_active_one_time_password_per_user', true)) {
            $this->deleteAllOneTimePasswords();
        }

        $this->oneTimePasswords()->create([
            'password' => Hash::make($code),
            'expires_at' => now()->addMinutes($expiresInMinutes),
        ]);

        return $code;
    }

    /**
     * Validate and consume a one-time password.
     *
     * @return array{ok: bool, message: string}
     */
    public function consumeOneTimePassword(string $code): array
    {
        $maxAttempts = (int) config('one-time-passwords.rate_limit_attempts.max_attempts_per_user', 5);
        $decaySeconds = (int) config('one-time-passwords.rate_limit_attempts.time_window_in_seconds', 60);
        $rateLimitKey = 'otp-consume:'.$this->getMorphClass().':'.$this->getKey();

        if (RateLimiter::tooManyAttempts($rateLimitKey, $maxAttempts)) {
            return ['ok' => false, 'message' => 'Too many attempts. Please try again later.'];
        }

        RateLimiter::hit($rateLimitKey, $decaySeconds);

        $otp = $this->oneTimePasswords()->latest('id')->first();

        if (! $otp || ! Hash::check($code, $otp->password)) {
            return ['ok' => false, 'message' => 'The one-time password is incorrect.'];
        }

        if ($otp->isExpired()) {
            $otp->delete();

            return ['ok' => false, 'message' => 'The one-time password has expired.'];
        }

        $this->deleteAllOneTimePasswords();
        RateLimiter::clear($rateLimitKey);

        return ['ok' => true, 'message' => 'OK'];
    }

    public function deleteAllOneTimePasswords(): void
    {
        $this->oneTimePasswords()->delete();
    }
}

