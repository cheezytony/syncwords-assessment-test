<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

class AuthenticationService
{
    public function login(array $credentials): array
    {
        $user = User::firstWhere([
            'email' => $credentials['email']
        ]);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new UnauthorizedException(__('auth.failed'));
        }

        $token = $user->createToken('access-token')->plainTextToken;

        return compact('user', 'token');
    }
}
