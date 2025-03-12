<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;

class PasswordResetService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function sendResetLink(string $email)
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $status = Password::sendResetLink(['email' => $email]);

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent successfully'])
            : response()->json(['message' => 'Failed to send reset link', 'status' => $status], 500);
    }

    public function resetPassword(array $data)
    {
        $status = Password::reset(
            $data,
            function ($user, string $password) {
                $this->userRepository->updatePassword($user, $password);
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully'])
            : response()->json(['message' => 'Invalid token'], 400);
    }
}
