<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService
{
    /**
     * @throws HttpException
     */
    public function signIn(array $loginInfo): array
    {
        $user = User::where('email', $loginInfo['email'])->first();
        if (Hash::check($loginInfo['password'], $user->password)) {
            $user->tokens()->delete();
            $token = $user->createToken("{$user->email}." . time());
            return [
                'name' => $user->name,
                'email' => $user->email,
                'token' => $token->plainTextToken
            ];
        } else {
            throw new HttpException(422, 'Wrong password');
        }
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
