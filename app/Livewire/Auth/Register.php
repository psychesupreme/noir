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
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|min:9|max:15',
            'password' => 'required|string|min:8|confirmed',
            'account_type' => 'required|in:retail,corporate,wholesale',
        ], [
            'name.required' => 'Full name is required.',
            'name.min' => 'Name must be at least 3 characters.',
            'email.unique' => 'An account with this email already exists.',
            'phone_number.required' => 'Phone number is required for delivery coordination.',
            'phone_number.min' => 'Enter a valid Kenyan phone number.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
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

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('components.layouts.auth');
    }
}
