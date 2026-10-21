<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Support\Permissions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->scopes(['email'])->redirect();
    }

    public function callback()
    {
        $facebookUser = Socialite::driver('facebook')->user();

        $facebookId = $facebookUser->getId();
        $email      = $facebookUser->getEmail(); // phone-only account mein null

        // 1) Pehle facebook_id se dhoondein (phone/email dono ke liye kaam karta hai)
        $user = User::where('facebook_id', $facebookId)->first();

        // 2) Na mile aur email ho to email se match karein
        if (!$user && $email) {
            $user = User::where('email', $email)->first();
        }

        // 3) Ab bhi na mile to naya user banayein
        if (!$user) {
            $user = new User();
            $user->password = bcrypt(Str::random(32));
        }

        $user->name        = $user->name ?: $facebookUser->getName();
        $user->facebook_id = $facebookId;
        $user->avatar      = $facebookUser->getAvatar();

        if ($email) {
            $user->email = $user->email ?: $email;
            $user->email_verified_at = $user->email_verified_at ?: now();
        } 

        $user->save();

        $role = $user->role
            ?? $user->roles()->first()
            ?? Role::where('name', Permissions::USER_ROLE)->firstOrFail();
        $user->syncAssignedRole($role);

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
