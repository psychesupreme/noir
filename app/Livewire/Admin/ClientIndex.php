<?php

namespace App\Livewire\Admin;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Admin\Traits\WithIntelligentSearch;

class ClientIndex extends Component
{
    use WithPagination, WithIntelligentSearch;

    // Filters
    public string $search = '';
    public string $typeFilter = 'all'; // all, corporate, individual
    public string $regionFilter = 'all'; // all, Nairobi, Kiambu
    public string $segmentFilter = 'all'; // all, vip, lapsed, active, new
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Detail panel
    public bool $showDetail = false;
    public ?int $selectedClientId = null;

    // CRUD Modal Form fields
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingClientId = null;

    public string $company_name = '';
    public string $kra_pin = '';
    public string $contact_name = '';
    public string $email = '';
    public string $phone = '';
    public string $region = 'Nairobi';
    public string $delivery_address = '';

    // Delete confirmation
    public bool $showDeleteModal = false;
    public ?int $deletingClientId = null;

    protected function rules(): array
    {
        $emailRule = 'required|email|max:255';
        if ($this->editingClientId) {
            $emailRule .= '|unique:clients,email,' . $this->editingClientId;
        } else {
            $emailRule .= '|unique:clients,email';
        }

        return [
            'contact_name' => 'required|string|min:3|max:255',
            'company_name' => 'nullable|string|max:255',
            'kra_pin' => 'nullable|string|min:11|max:15',
            'email' => $emailRule,
            'phone' => 'required|string|min:9|max:15',
            'region' => 'required|in:Nairobi,Kiambu',
            'delivery_address' => 'required|string|min:5|max:1000',
        ];
    }

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

    // CRUD Methods
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $clientId): void
    {
        $client = Client::findOrFail($clientId);
        $this->editingClientId = $client->id;
        $this->contact_name = $client->contact_name;
        $this->company_name = $client->company_name ?? '';
        $this->kra_pin = $client->kra_pin ?? '';
        $this->email = $client->email;
        $this->phone = $client->phone;
        $this->region = $client->region ?? 'Nairobi';
        $this->delivery_address = $client->delivery_address;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing && $this->editingClientId) {
            $client = Client::findOrFail($this->editingClientId);
            $client->update($validated);
            session()->flash('message', 'Client record updated successfully.');
        } else {
            Client::create($validated);
            session()->flash('message', 'New Client record created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $clientId): void
    {
        $this->deletingClientId = $clientId;
        $this->showDeleteModal = true;
    }

    public function deleteClient(): void
    {
        if ($this->deletingClientId) {
            $client = Client::findOrFail($this->deletingClientId);
            
            // Set user linkage client_id or user.client reference to null
            // Also nullify client_id on past orders so history is not lost
            \App\Models\Order::where('client_id', $client->id)->update(['client_id' => null]);
            
            $client->delete();
            session()->flash('message', 'Client record deleted from database successfully.');
        }

        $this->showDeleteModal = false;
        $this->deletingClientId = null;
    }

    protected function resetForm(): void
    {
        $this->reset([
            'editingClientId', 'contact_name', 'company_name', 'kra_pin', 
            'email', 'phone', 'region', 'delivery_address', 'isEditing'
        ]);
        $this->region = 'Nairobi';
    }

    public function render()
    {
        $query = Client::withCount('orders')
            ->withSum(['orders as total_spent' => function ($q) {
                $q->where('status', '!=', 'cancelled');
            }], 'total_amount');

        // Type filter
        if ($this->typeFilter === 'corporate') {
            $query->whereNotNull('kra_pin')->where('kra_pin', '!=', '');
        } elseif ($this->typeFilter === 'individual') {
            $query->where(function ($q) {
                $q->whereNull('kra_pin')->orWhere('kra_pin', '');
            });
        }

        // Region filter
        if ($this->regionFilter !== 'all') {
            $query->where('region', $this->regionFilter);
        }

        // Segment filter
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

        // Intelligent Search Parser
        if (!empty($this->search)) {
            $this->parseAndApplySearch(
                $query,
                $this->search,
                ['contact_name', 'company_name', 'email', 'phone', 'region', 'delivery_address'],
                [
                    'type' => function ($q, $op, $val) {
                        if ($val === 'corporate') {
                            $q->whereNotNull('kra_pin')->where('kra_pin', '!=', '');
                        } else {
                            $q->where(fn($sub) => $sub->whereNull('kra_pin')->orWhere('kra_pin', ''));
                        }
                    },
                    'region' => 'region',
                    'spent' => function ($q, $op, $val) {
                        $q->having('total_spent', $op, (float)$val);
                    }
                ]
            );
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
