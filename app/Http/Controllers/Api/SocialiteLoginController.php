<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialiteLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteLoginController extends Controller
{
    use \App\Traits\HttpResponses;
    public function redirectToGoogle()
    {
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function handleGoogleCallback()
    {
        $socialiteUser = Socialite::driver('google')->stateless()->user();
        $user = User::firstOrCreate(
          [
              'email' => $socialiteUser->getEmail(),
          ],
          [
              'name' => $socialiteUser->getName(),
              'password' => Hash::make(Str::random(8))
          ]
        );
        SocialiteLogin::firstOrCreate(
            [
                'user_id' => $user->id,
                'provider' => 'google'
            ],
            [
                'provider_id' => $socialiteUser->getId()
            ]
        );

        Auth::login($user);
        return $this->success([
            'user' => $user,
            'access_token' => $user->createToken('google-token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }
}
