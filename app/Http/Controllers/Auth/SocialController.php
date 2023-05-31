<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
         if (!$provider) {
            // Handle the case when $provider is null
            // Redirect or display an error message
            return redirect()->back()->withErrors(['message' => 'Invalid provider']);
        }
        $user = Socialite::driver($provider)->user();
        // You can now create or update the user record in your database
        // using $user->getId(), $user->getName(), $user->getEmail(), etc.

        // Authenticate the user
        Auth::login($user, true);

        // Redirect the user to the desired page
        // return redirect('/dashboard');
        // Redirect the user to the desired page
    }
}
