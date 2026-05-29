<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use App\Models\Order;
use App\Services\EtimsService;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Admin\Traits\WithIntelligentSearch;

class OrderIndex extends Component
{
    use WithPagination, WithIntelligentSearch;

    public string $search = '';
    public string $statusFilter = 'all';
    public string $branchFilter = 'all';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingBranchFilter(): void
    {
        $this->resetPage();
    }
    /**
     * Manually override the assigned branch hub allocation for fulfillment optimizations.
     */
    public function updateBranch(int $orderId, int $branchId): void
    {
        Order::where('id', $orderId)->update(['branch_id' => $branchId]);
    }

    /**
     * Update order state and automatically register compliance parameters on approval events.
     */
    public function updateStatus(int $orderId, string $newStatus, EtimsService $etims): void
    {
        $validStatuses = ['pending', 'approved', 'processing', 'delivered', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            return;
        }

        $order = Order::with(['client', 'products'])->find($orderId);
        if (!$order) {
            return;
        }

        $orderService = app(\App\Services\OrderService::class);

        if ($newStatus === 'approved') {
            $orderService->approve($order);
        } elseif ($newStatus === 'cancelled') {
            $orderService->cancel($order);
        } else {
            $order->update(['status' => $newStatus]);
            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');
        }
    }

    public function render()
    {
        $query = Order::with(['client', 'products', 'etimsInvoice', 'branch']);

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->branchFilter !== 'all') {
            if ($this->branchFilter === 'unassigned') {
                $query->whereNull('branch_id');
            } else {
                $query->where('branch_id', $this->branchFilter);
            }
        }

        // Use intelligent search parser for dynamic text search & commands
        if (!empty($this->search)) {
            // Map order ID parsing specifically if user types ORD- or NB-ORD-
            $searchTerm = $this->search;
            if (preg_match('/NB-ORD-(\d+)/i', $searchTerm, $matches)) {
                $this->parseAndApplySearch($query, $matches[1], ['id'], []);
            } else {
                $this->parseAndApplySearch(
                    $query,
                    $searchTerm,
                    ['id', 'client.contact_name', 'client.company_name', 'client.email', 'client.phone'],
                    [
                        'status' => 'status',
                        'amount' => 'total_amount',
                        'branch' => 'branch_id',
                    ]
                );
            }
        }

        return view('livewire.admin.order-index', [
            'orders'   => $query->latest()->paginate(15),
            'branches' => Branch::where('is_active', true)->get()
        ])->layout('components.layouts.admin');
    }
}