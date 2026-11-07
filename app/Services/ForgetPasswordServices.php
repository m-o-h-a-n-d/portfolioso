<?php

namespace App\Services;

use App\Notifications\SendOtpNotify;
use App\Repository\ForgetPasswordRepository;
use Illuminate\Support\Str;

class ForgetPasswordServices
{
    public function __construct(protected ForgetPasswordRepository $forgetPasswordRepository) {}

    public function sendOtp(string $email): bool
    {
        $user = $this->forgetPasswordRepository->getUserByEmail($email);
        if (! $user) {
            return false;
        }

        $otp = $user->createOneTimePassword();
        $user->notify(new SendOtpNotify($otp));

        return true;
    }

    public function verifyOtp(array $data): array
    {
        $user = $this->forgetPasswordRepository->getUserByEmail($data['email']);
        if (! $user) {
            return ['ok' => false, 'message' => 'User not found'];
        }

        $result = $user->consumeOneTimePassword((string) $data['otp']);
        if (! $result['ok']) {
            return ['ok' => false, 'message' => $result['message']];
        }

        $plainToken = Str::random(64);
        $hashedToken = hash('sha256', $plainToken);
        $expiresAt = now()->addMinutes(15);

        $this->forgetPasswordRepository->saveResetToken($user->email, $hashedToken, $expiresAt);

        return ['ok' => true, 'token' => $plainToken];
    }

    public function resetPassword(array $data): array
    {
        $tokenRow = $this->forgetPasswordRepository->getResetTokenByEmail($data['email']);
        if (! $tokenRow) {
            return ['ok' => false, 'message' => 'Invalid or expired reset token'];
        }

        if (now()->greaterThan($tokenRow->expires_at)) {
            $this->forgetPasswordRepository->deleteResetTokenByEmail($data['email']);

            return ['ok' => false, 'message' => 'Reset token expired'];
        }

        $hashedToken = hash('sha256', $data['token']);
        if (! hash_equals($tokenRow->token, $hashedToken)) {
            return ['ok' => false, 'message' => 'Invalid reset token'];
        }

        $user = $this->forgetPasswordRepository->getUserByEmail($data['email']);
        if (! $user) {
            return ['ok' => false, 'message' => 'User not found'];
        }

        $user->password = $data['password'];
        $user->save();

        $this->forgetPasswordRepository->deleteResetTokenByEmail($data['email']);
        $user->deleteAllOneTimePasswords();

        return ['ok' => true];
    }
}
