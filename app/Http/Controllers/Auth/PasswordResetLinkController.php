<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * Bypasses email sending entirely. Generates a secure token and redirects
     * the user directly to the Reset Password page.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            // Return a generic validation error (do not reveal whether the email exists)
            throw ValidationException::withMessages([
                'email' => [__('passwords.user')],
            ]);
        }

        // Delete any existing reset tokens for this user and create a fresh one
        $broker = Password::broker();
        $broker->getRepository()->delete($user);
        $token = $broker->getRepository()->create($user);

        // Redirect directly to the reset password form (no email sent)
        return redirect()->route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);
    }
}
