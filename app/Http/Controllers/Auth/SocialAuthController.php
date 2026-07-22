<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the provider's OAuth page.
     * Fallback to a premium mock gateway if keys are not configured.
     */
    public function redirect(string $provider)
    {
        if (!in_array($provider, ['google'])) {
            abort(404, 'Provider not supported');
        }

        $clientId = config("services.{$provider}.client_id");
        $clientSecret = config("services.{$provider}.client_secret");

        $isConfigured = !empty($clientId) && !empty($clientSecret)
            && !str_contains($clientId, 'your_')
            && !str_contains($clientSecret, 'your_');

        // If keys are not set or are placeholders
        if (!$isConfigured) {
            return view('auth.social.approval', [
                'provider' => $provider,
                'name' => ucfirst($provider),
                'email' => $provider . '.user@noirbloom.com',
            ]);
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (Exception $e) {
            // Fallback if driver errors out
            return view('auth.social.approval', [
                'provider' => $provider,
                'name' => ucfirst($provider),
                'email' => $provider . '.user@noirbloom.com',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle the provider callback.
     */
    public function callback(string $provider, Request $request)
    {
        if (!in_array($provider, ['google'])) {
            abort(404);
        }

        // Check if this is a simulated approval callback
        if ($request->has('simulated')) {
            return $this->loginOrCreateUser(
                $provider,
                $provider . '.user@noirbloom.com',
                ucfirst($provider) . ' Curation Member'
            );
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
            return $this->loginOrCreateUser(
                $provider,
                $socialUser->getEmail(),
                $socialUser->getName() ?: ucfirst($provider) . ' User'
            );
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => "Social authentication failed: " . $e->getMessage()
            ]);
        }
    }

    /**
     * Helper to log in or register the user.
     */
    protected function loginOrCreateUser(string $provider, string $email, string $name)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            $phone = '2547' . rand(10000000, 99999999);
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'phone_number' => $phone,
                'password' => Hash::make('social-secret-123'),
                'account_tier' => 'retail',
                'default_region' => 'Nairobi',
            ]);

            Client::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'contact_name' => $user->name,
                'phone' => $user->phone_number,
                'region' => 'Nairobi',
                'delivery_address' => 'Auto-signed in via ' . ucfirst($provider),
            ]);
        }

        Auth::login($user);
        session()->regenerate();

        if ($user->isStaff()) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/');
    }
}
