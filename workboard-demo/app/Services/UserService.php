<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function __construct(private readonly UserRepository $users) {}

    public function register(array $attributes): User
    {
        return $this->users->create($attributes);
    }

    public function updateProfile(User $user, array $attributes): User
    {
        if (isset($attributes['email']) && $attributes['email'] !== $user->email) {
            $attributes['email_verified_at'] = null;
        }

        return $this->users->update($user, $attributes);
    }

    public function changePassword(User $user, string $password): User
    {
        return $this->users->update($user, [
            'password' => $password,
            'must_change_password' => false,
        ]);
    }

    public function delete(User $user): void
    {
        $this->users->delete($user);
    }

    public function authenticateApi(string $email, string $password): ?User
    {
        $user = $this->users->findByEmail($email);

        return $user && Hash::check($password, $user->password) ? $user : null;
    }

    public function revokeCurrentToken(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }

    public function issueToken(User $user, string $deviceName): string
    {
        return $user->createToken($deviceName)->plainTextToken;
    }

    public function resetPassword(User $user, string $password): void
    {
        $this->users->forceUpdate($user, ['password' => $password, 'remember_token' => Str::random(60)]);
        event(new PasswordReset($user));
    }

    public function sendVerification(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }
        $user->sendEmailVerificationNotification();

        return true;
    }

    public function verifyEmail(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }
        $verified = $user->markEmailAsVerified();
        if ($verified) {
            event(new Verified($user));
        }

        return $verified;
    }
}
