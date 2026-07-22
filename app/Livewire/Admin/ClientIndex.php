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
    public string $activeTab = 'overview'; // overview, deals, timeline

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

    // Deal Modal Form fields
    public bool $showDealModal = false;
    public bool $isEditingDeal = false;
    public ?int $editingDealId = null;
    public string $dealTitle = '';
    public string $dealStage = 'lead';
    public int $dealValue = 0;
    public ?string $dealClosedAt = null;

    // Deal Delete confirmation
    public bool $showDeleteDealModal = false;
    public ?int $deletingDealId = null;

    // Timeline Form fields
    public string $logEventType = 'call'; // call, email, meeting, note
    public string $logDescription = '';

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
        $this->activeTab = 'overview';
    }

    public function closeDetail(): void
    {
        $this->showDetail = false;
        $this->selectedClientId = null;
        $this->activeTab = 'overview';
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
        $timelineEvents = collect();
        if ($this->selectedClientId) {
            $selectedClient = Client::with([
                'orders' => function ($q) {
                    $q->with(['products', 'payments'])->latest();
                },
                'deals' => function ($q) {
                    $q->latest();
                },
                'crmTimelineLogs' => function ($q) {
                    $q->with('user')->latest();
                }
            ])->find($this->selectedClientId);

            if ($selectedClient) {
                // 1. Map Orders
                $orderEvents = $selectedClient->orders->map(function ($order) {
                    return [
                        'type' => 'order',
                        'icon' => 'shopping-bag',
                        'title' => 'Order Placed (#NB-ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ')',
                        'description' => 'Gross Amount: Ksh ' . number_format($order->total_amount) . ' · Status: ' . ucfirst($order->status),
                        'timestamp' => $order->created_at,
                        'color' => 'bg-emerald-950/40 text-emerald-400 border-emerald-900/30',
                    ];
                });

                // 2. Map CRM Manual Logs
                $crmEvents = $selectedClient->crmTimelineLogs->map(function ($log) {
                    $icons = [
                        'call' => 'phone',
                        'email' => 'envelope',
                        'meeting' => 'users',
                        'note' => 'document-text',
                    ];
                    $colors = [
                        'call' => 'bg-blue-950/40 text-blue-400 border-blue-900/30',
                        'email' => 'bg-purple-950/40 text-purple-400 border-purple-900/30',
                        'meeting' => 'bg-amber-950/40 text-amber-400 border-amber-900/30',
                        'note' => 'bg-neutral-900 text-neutral-400 border-neutral-800',
                    ];
                    return [
                        'type' => 'crm_log',
                        'id' => $log->id,
                        'icon' => $icons[$log->event_type] ?? 'chat-bubble-left',
                        'title' => ucfirst($log->event_type) . ' logged by ' . ($log->user?->name ?? 'Staff'),
                        'description' => $log->description,
                        'timestamp' => $log->created_at,
                        'color' => $colors[$log->event_type] ?? 'bg-neutral-900 text-neutral-400 border-neutral-800',
                    ];
                });

                // 3. Map Loyalty Transactions if client has user
                $loyaltyEvents = collect();
                if ($selectedClient->user_id) {
                    $transactions = \App\Models\LoyaltyTransaction::where('user_id', $selectedClient->user_id)->latest()->get();
                    $loyaltyEvents = $transactions->map(function ($tx) {
                        return [
                            'type' => 'loyalty',
                            'icon' => 'gift',
                            'title' => 'Loyalty Points ' . ($tx->type === 'earn' ? 'Earned' : 'Redeemed'),
                            'description' => ($tx->type === 'earn' ? '+' : '-') . $tx->points . ' points: ' . ($tx->description ?? 'Transaction reward'),
                            'timestamp' => $tx->created_at,
                            'color' => 'bg-rose-950/40 text-rose-400 border-rose-900/30',
                        ];
                    });
                }

                // 4. Merge and sort
                $timelineEvents = $orderEvents
                    ->concat($crmEvents)
                    ->concat($loyaltyEvents)
                    ->sortByDesc('timestamp')
                    ->values();
            }
        }

        // Stats
        $totalClients = Client::count();
        $corporateClients = Client::whereNotNull('kra_pin')->where('kra_pin', '!=', '')->count();
        $nairobiClients = Client::where('region', 'Nairobi')->count();
        $kiambuClients = Client::where('region', 'Kiambu')->count();

        return view('livewire.admin.client-index', [
            'clients' => $query->paginate(15),
            'selectedClient' => $selectedClient,
            'timelineEvents' => $timelineEvents,
            'totalClients' => $totalClients,
            'corporateClients' => $corporateClients,
            'nairobiClients' => $nairobiClients,
            'kiambuClients' => $kiambuClients,
        ])->layout('components.layouts.admin', ['title' => 'Noir & Bloom | Clients']);
    }

    // ─── CRM Deal Management Methods ───
    public function openCreateDealModal(): void
    {
        $this->resetDealForm();
        $this->isEditingDeal = false;
        $this->showDealModal = true;
    }

    public function openEditDealModal(int $dealId): void
    {
        $deal = \App\Models\Deal::findOrFail($dealId);
        $this->editingDealId = $deal->id;
        $this->dealTitle = $deal->title;
        $this->dealStage = $deal->stage;
        $this->dealValue = $deal->deal_value;
        $this->dealClosedAt = $deal->closed_at ? $deal->closed_at->format('Y-m-d\TH:i') : null;
        $this->isEditingDeal = true;
        $this->showDealModal = true;
    }

    public function saveDeal(): void
    {
        $this->validate([
            'dealTitle' => 'required|string|min:3|max:255',
            'dealStage' => 'required|in:lead,proposal,sample,negotiation,won,lost',
            'dealValue' => 'required|integer|min:0',
            'dealClosedAt' => 'nullable|date',
        ]);

        if ($this->isEditingDeal && $this->editingDealId) {
            $deal = \App\Models\Deal::findOrFail($this->editingDealId);
            $deal->update([
                'title' => $this->dealTitle,
                'stage' => $this->dealStage,
                'deal_value' => $this->dealValue,
                'closed_at' => $this->dealClosedAt ? \Carbon\Carbon::parse($this->dealClosedAt) : null,
            ]);
            session()->flash('deal_message', 'B2B Deal updated successfully.');
        } else {
            \App\Models\Deal::create([
                'client_id' => $this->selectedClientId,
                'title' => $this->dealTitle,
                'stage' => $this->dealStage,
                'deal_value' => $this->dealValue,
                'closed_at' => $this->dealClosedAt ? \Carbon\Carbon::parse($this->dealClosedAt) : null,
            ]);
            session()->flash('deal_message', 'New B2B Deal added successfully.');
        }

        $this->showDealModal = false;
        $this->resetDealForm();
    }

    public function confirmDeleteDeal(int $dealId): void
    {
        $this->deletingDealId = $dealId;
        $this->showDeleteDealModal = true;
    }

    public function deleteDeal(): void
    {
        if ($this->deletingDealId) {
            \App\Models\Deal::destroy($this->deletingDealId);
            session()->flash('deal_message', 'B2B Deal deleted successfully.');
        }
        $this->showDeleteDealModal = false;
        $this->deletingDealId = null;
    }

    protected function resetDealForm(): void
    {
        $this->reset(['editingDealId', 'dealTitle', 'dealStage', 'dealValue', 'dealClosedAt', 'isEditingDeal']);
        $this->dealStage = 'lead';
        $this->dealValue = 0;
    }

    // ─── CRM Timeline Log Methods ───
    public function saveTimelineLog(): void
    {
        $this->validate([
            'logDescription' => 'required|string|min:3|max:1000',
            'logEventType' => 'required|in:call,email,meeting,note',
        ]);

        \App\Models\CrmTimelineLog::create([
            'client_id' => $this->selectedClientId,
            'user_id' => auth()->id(),
            'event_type' => $this->logEventType,
            'description' => $this->logDescription,
        ]);

        $this->logDescription = '';
        $this->logEventType = 'call';
        session()->flash('timeline_message', 'Interaction log entry added successfully.');
    }

    public function deleteTimelineLog(int $logId): void
    {
        \App\Models\CrmTimelineLog::destroy($logId);
        session()->flash('timeline_message', 'Interaction log entry removed.');
    }
}
