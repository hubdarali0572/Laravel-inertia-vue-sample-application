<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'              => $googleUser->getName(),
                'google_id'         => $googleUser->getId(),
                'avatar'            => $googleUser->getAvatar(),
                'email_verified_at' => now(),
            ]
        );

        // Sirf naye user ko role milega
        // if ($user->wasRecentlyCreated) {
        //     $user->assignRole('user'); // apne role ka naam likhein
        // }

          // Sirf naye user ko role milega
        if ($user->wasRecentlyCreated) {
            $user->role_id = Role::where('name', 'user')->value('id');
            $user->save();
        }

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
