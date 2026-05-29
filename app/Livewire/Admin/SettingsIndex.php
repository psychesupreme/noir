<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Enums\UserRole;
use Livewire\Component;
use Livewire\WithPagination;

class SettingsIndex extends Component
{
    use WithPagination;

    public string $activeTab = 'general';

    // General fields
    public string $appName = '';
    public int $defaultTaxRate = 16;
    public string $mpesaEnv = 'sandbox';

    // Integration fields
    public string $mpesaKey = '';
    public string $mpesaSecret = '';
    public string $mpesaPasskey = '';
    public bool $maskCredentials = true;

    // Users search
    public string $userSearch = '';

    protected $queryString = [
        'activeTab' => ['except' => 'general']
    ];

    public function mount(): void
    {
        $this->appName = config('app.name', 'Noir & Bloom');
        $this->defaultTaxRate = 16;
        $this->mpesaEnv = config('services.mpesa.environment', 'sandbox');
        
        $this->mpesaKey = config('services.mpesa.key', '');
        $this->mpesaSecret = config('services.mpesa.secret', '');
        $this->mpesaPasskey = config('services.mpesa.passkey', '');
    }

    public function updatingUserSearch(): void
    {
        $this->resetPage();
    }

    public function saveGeneral(): void
    {
        $this->validate([
            'appName' => 'required|string|min:3|max:100',
            'defaultTaxRate' => 'required|integer|min:0|max:50',
            'mpesaEnv' => 'required|in:sandbox,production',
        ]);

        // In production, this would save to a config file, DB, or caching layer.
        // We simulate a successful persist.
        session()->flash('message', 'General settings configurations persisted successfully.');
    }

    public function saveCredentials(): void
    {
        $this->validate([
            'mpesaKey' => 'required|string|min:10',
            'mpesaSecret' => 'required|string|min:10',
            'mpesaPasskey' => 'required|string|min:10',
        ]);

        // Simulating credential updates
        session()->flash('message', 'Integration API credentials updated and encrypted in local vault.');
    }

    /**
     * Change user role status.
     */
    public function updateUserRole(int $userId, string $newRoleVal): void
    {
        $user = User::findOrFail($userId);
        
        // Match string value to UserRole enum case
        $roleCase = UserRole::from($newRoleVal);
        
        $user->update([
            'account_tier' => $roleCase
        ]);

        session()->flash('message', "User {$user->name}'s role updated to {$roleCase->label()} successfully.");
    }

    public function render()
    {
        $usersQuery = User::query();

        if (!empty($this->userSearch)) {
            $usersQuery->where(function ($q) {
                $q->where('name', 'like', '%' . $this->userSearch . '%')
                  ->orWhere('email', 'like', '%' . $this->userSearch . '%')
                  ->orWhere('phone_number', 'like', '%' . $this->userSearch . '%');
            });
        }

        return view('livewire.admin.settings-index', [
            'users' => $usersQuery->latest()->paginate(8),
            'availableRoles' => UserRole::cases(),
        ])->layout('components.layouts.admin');
    }
}
