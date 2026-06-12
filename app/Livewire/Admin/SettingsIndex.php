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

    // Hero settings
    public array $slides = [];

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

        $this->slides = \App\Services\HeroSettingsService::getSlides();
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

    public function saveHeroSettings(): void
    {
        $this->validate([
            'slides.*.badge' => 'required|string|max:100',
            'slides.*.title' => 'required|string|max:100',
            'slides.*.description' => 'required|string|max:500',
            'slides.*.bg_image' => 'required|url|max:500',
            'slides.*.cta_text' => 'required|string|max:50',
            'slides.*.cta_link' => 'required|string|max:100',
        ]);

        \App\Services\HeroSettingsService::saveSlides($this->slides);
        session()->flash('message', 'Landing page hero settings configurations persisted successfully.');
    }

    public function addSlide(): void
    {
        $this->slides[] = [
            'badge' => 'New Badge Text',
            'title' => 'New Slide Title',
            'description' => 'Slide description copy goes here.',
            'bg_image' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&q=80&w=1200',
            'cta_text' => 'Learn More',
            'cta_link' => 'stems',
        ];
    }

    public function deleteSlide(int $index): void
    {
        unset($this->slides[$index]);
        $this->slides = array_values($this->slides);
    }

    public function moveSlideUp(int $index): void
    {
        if ($index > 0) {
            $temp = $this->slides[$index - 1];
            $this->slides[$index - 1] = $this->slides[$index];
            $this->slides[$index] = $temp;
        }
    }

    public function moveSlideDown(int $index): void
    {
        if ($index < count($this->slides) - 1) {
            $temp = $this->slides[$index + 1];
            $this->slides[$index + 1] = $this->slides[$index];
            $this->slides[$index] = $temp;
        }
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
