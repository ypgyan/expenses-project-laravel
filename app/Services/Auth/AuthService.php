<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function signIn(array $loginInfo): array
    {

    }

    public function signUp(array $registerInfo): User
    {
        $user = new User();
        $user->name = $registerInfo['fullName'];
        $user->email = $registerInfo['email'];
        $user->password = Hash::make($registerInfo['password']);
        $user->save();
        return $user;
    }
}
