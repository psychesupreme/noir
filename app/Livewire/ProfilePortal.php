<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use App\Models\Client;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class ProfilePortal extends Component
{
    // Tabs
    public string $activeTab = 'client'; // client, partner, logistics

    // Profile Details Form
    public string $name = '';
    public string $email = '';
    public string $phone_number = '';
    public string $kra_pin = '';
    public string $default_delivery_address = '';
    public string $default_region = 'Nairobi';

    // Password Update
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    // Partner B2B Form
    public string $partner_company = '';
    public string $partner_product_interest = 'wholesale_stems'; // wholesale_stems, event_supply, florist_partner
    public string $partner_message = '';
    public bool $partnerSubmitted = false;

    // Logistics status update success feedback
    public ?int $updatedOrderId = null;

    protected $rules = [
        'name' => 'required|string|min:3',
        'email' => 'required|email',
        'phone_number' => 'nullable|string',
        'kra_pin' => 'nullable|string|min:11|max:15',
        'default_delivery_address' => 'nullable|string|min:6',
        'default_region' => 'required|in:Nairobi,Kiambu',
    ];

    public function mount(): void
    {
        $user = auth()->user();
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone_number = $user->phone_number ?? '';
            $this->kra_pin = $user->kra_pin ?? '';
            $this->default_delivery_address = $user->default_delivery_address ?? '';
            $this->default_region = $user->default_region ?? 'Nairobi';
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function updateProfile(): void
    {
        $this->validate();

        $user = auth()->user();
        if ($user) {
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'kra_pin' => strtoupper($this->kra_pin),
                'default_delivery_address' => $this->default_delivery_address,
                'default_region' => $this->default_region,
            ]);

            // Sync with CRM Client table
            Client::updateOrCreate(
                ['email' => $user->email],
                [
                    'user_id' => $user->id,
                    'contact_name' => $user->name,
                    'phone' => $user->phone_number,
                    'kra_pin' => $user->kra_pin,
                    'delivery_address' => $user->default_delivery_address,
                    'region' => $user->default_region,
                ]
            );

            session()->flash('success_profile', 'Atelier profile metrics updated successfully.');
        }
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();
        if ($user && Hash::check($this->current_password, $user->password)) {
            $user->update([
                'password' => Hash::make($this->new_password),
            ]);
            $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
            session()->flash('success_password', 'Password updated successfully.');
        } else {
            session()->flash('error_password', 'Current password credentials do not match.');
        }
    }

    public function submitPartnerRequest(): void
    {
        $this->validate([
            'partner_company' => 'required|string|min:3',
            'partner_message' => 'required|string|min:10',
        ]);

        // Mock saving partner inquiry details
        $this->partnerSubmitted = true;
        session()->flash('success_partner', 'Strategic partner request sent to Atelier review board.');
    }

    public function updateLogisticsStatus(int $orderId, string $status): void
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->update(['status' => $status]);
            $this->updatedOrderId = $orderId;
            session()->flash('success_logistics', 'Delivery run nb-ord-' . $orderId . ' status set to ' . strtoupper($status));
        }
    }

    public function render()
    {
        $user = auth()->user();
        $userOrders = collect();
        $assignedRuns = collect();

        if ($user) {
            // Load Client order history
            $client = Client::where('email', $user->email)->first();
            if ($client) {
                $userOrders = $client->orders()->with(['products', 'payments'])->latest()->get();
            }

            // Load Logistics runs (All orders pending/processing/delivered for drivers/riders dashboard)
            $assignedRuns = Order::with(['client', 'products'])->latest()->limit(15)->get();
        }

        return view('livewire.profile-portal', [
            'userOrders' => $userOrders,
            'assignedRuns' => $assignedRuns,
            'user' => $user,
        ])->layout('components.layouts.app');
    }
}
