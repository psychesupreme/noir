<?php

namespace App\Livewire\Admin;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ClientIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = 'all'; // all, corporate, individual
    public string $regionFilter = 'all'; // all, Nairobi, Kiambu
    public string $segmentFilter = 'all'; // all, vip, lapsed, active, new
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Detail panel
    public bool $showDetail = false;
    public ?int $selectedClientId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingRegionFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSegmentFilter(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function viewClient(int $clientId): void
    {
        $this->selectedClientId = $clientId;
        $this->showDetail = true;
    }

    public function closeDetail(): void
    {
        $this->showDetail = false;
        $this->selectedClientId = null;
    }

    public function render()
    {
        $query = Client::withCount('orders')
            ->withSum(['orders as total_spent' => function ($q) {
                $q->where('status', '!=', 'cancelled');
            }], 'total_amount');

        if ($this->typeFilter === 'corporate') {
            $query->whereNotNull('kra_pin')->where('kra_pin', '!=', '');
        } elseif ($this->typeFilter === 'individual') {
            $query->where(function ($q) {
                $q->whereNull('kra_pin')->orWhere('kra_pin', '');
            });
        }

        if ($this->regionFilter !== 'all') {
            $query->where('region', $this->regionFilter);
        }

        if ($this->segmentFilter === 'vip') {
            $query->having('total_spent', '>', 50000);
        } elseif ($this->segmentFilter === 'lapsed') {
            $query->whereDoesntHave('orders', function ($q) {
                $q->where('created_at', '>=', now()->subDays(60));
            });
        } elseif ($this->segmentFilter === 'active') {
            $query->whereHas('orders', function ($q) {
                $q->where('created_at', '>=', now()->subDays(60));
            });
        } elseif ($this->segmentFilter === 'new') {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('contact_name', 'like', '%' . $this->search . '%')
                  ->orWhere('company_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        // Selected client detail
        $selectedClient = null;
        if ($this->selectedClientId) {
            $selectedClient = Client::with(['orders' => function ($q) {
                $q->with(['products', 'payments'])->latest()->limit(10);
            }])->find($this->selectedClientId);
        }

        // Stats
        $totalClients = Client::count();
        $corporateClients = Client::whereNotNull('kra_pin')->where('kra_pin', '!=', '')->count();
        $nairobiClients = Client::where('region', 'Nairobi')->count();
        $kiambuClients = Client::where('region', 'Kiambu')->count();

        return view('livewire.admin.client-index', [
            'clients' => $query->paginate(15),
            'selectedClient' => $selectedClient,
            'totalClients' => $totalClients,
            'corporateClients' => $corporateClients,
            'nairobiClients' => $nairobiClients,
            'kiambuClients' => $kiambuClients,
        ])->layout('components.layouts.admin');
    }
}
