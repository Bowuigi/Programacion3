<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Socialite;
use App\Models\User;

class OAuthController extends Controller
{
    private function checkValidProvider(string $provider)
    {
        if (in_array($provider, [
            'google',
        ])) {
            return true;
        }
        return response('Invalid OAuth provider', 400);
    }

    public function redirect(string $provider)
    {
        $status = $this->checkValidProvider($provider);
        if ($status !== true) {
            return $status;
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        $status = $this->checkValidProvider($provider);
        if ($status !== true) {
            return $status;
        }

        $providerUser = Socialite::driver($provider)->user();

        $user = User::updateOrCreate(
            ['provider_id' => $providerUser->id],
            [
                'name' => $providerUser->name,
                'email' => $providerUser->email,
                'provider_name' => $provider,
                'provider_token' => $providerUser->token,
                'provider_refresh_token' => $providerUser->refreshToken,
            ]
        );

        Auth::login($user, true);

        return redirect()->intended('/dashboard');
    }
}
