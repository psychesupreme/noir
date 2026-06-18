<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone_number = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $account_type = 'retail';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[A-Za-z\s\-]+$/'],
            'email' => ['required', 'email:rfc', 'unique:users,email', 'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/'],
            'phone_number' => ['required', 'string', 'regex:/^(7|1)[0-9]{8}$/'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',      // at least one uppercase letter
                'regex:/[a-z]/',      // at least one lowercase letter
                'regex:/[0-9]/',      // at least one digit
                'regex:/[@$!%*#?&]/'  // at least one special character
            ],
            'account_type' => ['required', 'in:retail,corporate,wholesale'],
        ], [
            'name.required' => 'Full name is required.',
            'name.min' => 'Name must be at least 3 characters.',
            'name.regex' => 'Name must contain only letters, spaces, or hyphens.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.regex' => 'Email contains characters not permitted in strict mode.',
            'email.unique' => 'An account with this email already exists.',
            'phone_number.required' => 'Phone number is required for delivery coordination.',
            'phone_number.regex' => 'Please enter a valid 9-digit Kenyan phone number starting with 7 or 1 (e.g. 712345678).',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Passwords do not match.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*#?&).',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
            'account_tier' => $validated['account_type'],
            'default_region' => 'Nairobi',
        ]);

        // Link User to Client CRM record
        $client = \App\Models\Client::where('email', $user->email)->first();
        if ($client) {
            $client->update(['user_id' => $user->id]);
        } else {
            \App\Models\Client::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'contact_name' => $user->name,
                'phone' => $user->phone_number,
                'region' => 'Nairobi',
                'delivery_address' => 'Pending first order',
            ]);
        }

        event(new Registered($user));
        Auth::login($user);
        session()->regenerate();

        $this->redirect('/', navigate: true);
    }

    /**
     * Simulate a social login/registration.
     */
    public function socialLogin(string $provider): void
    {
        if (!in_array($provider, ['google', 'apple', 'microsoft'])) {
            return;
        }

        $randomId = rand(1000, 9999);
        $name = ucfirst($provider) . ' Curation Member';
        $email = $provider . '.' . $randomId . '@noirbloom.co.ke';
        $phone = '7' . rand(10000000, 99999999);

        // Find or create user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'phone_number' => $phone,
                'password' => Hash::make(bin2hex(random_bytes(10))),
                'account_tier' => 'retail',
                'default_region' => 'Nairobi',
            ]);

            \App\Models\Client::create([
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

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('components.layouts.auth');
    }
}
