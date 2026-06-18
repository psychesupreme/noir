<?php

namespace App\Livewire\Admin;

use App\Models\Vendor;
use Livewire\Component;
use Livewire\WithPagination;

class VendorIndex extends Component
{
    use WithPagination;

    public function mount(): void
    {
        if (request()->query('action') === 'create') {
            $this->create();
        }
    }

    public string $search = '';
    public string $activeFilter = 'all';
    
    // View state
    public ?int $viewingVendorId = null;

    // Form attributes
    public ?Vendor $editingVendor = null;
    public string $name = '';
    public string $contact_person = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $payment_terms = 'Cash on Delivery';
    public int $reliability_rating = 3;
    public bool $is_active = true;

    public bool $showModal = false;
    public bool $isEditMode = false;

    public function viewVendor(int $id): void
    {
        $this->viewingVendorId = $id;
    }

    public function closeView(): void
    {
        $this->viewingVendorId = null;
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'contact_person' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:50',
        'address' => 'nullable|string',
        'payment_terms' => 'required|string|in:Cash on Delivery,Net 7,Net 14,Net 30,Net 60',
        'reliability_rating' => 'required|integer|min:1|max:5',
        'is_active' => 'required|boolean',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingActiveFilter(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetErrorBag();
        $this->editingVendor = null;
        $this->name = '';
        $this->contact_person = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->payment_terms = 'Cash on Delivery';
        $this->reliability_rating = 3;
        $this->is_active = true;
        
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $this->resetErrorBag();
        $vendor = Vendor::findOrFail($id);
        $this->editingVendor = $vendor;
        $this->name = $vendor->name;
        $this->contact_person = $vendor->contact_person;
        $this->email = $vendor->email ?? '';
        $this->phone = $vendor->phone ?? '';
        $this->address = $vendor->address ?? '';
        $this->payment_terms = $vendor->payment_terms;
        $this->reliability_rating = $vendor->reliability_rating;
        $this->is_active = $vendor->is_active;

        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validatedData = $this->validate();

        if ($this->isEditMode && $this->editingVendor) {
            $this->editingVendor->update($validatedData);
            session()->flash('message', 'Vendor updated successfully.');
        } else {
            Vendor::create($validatedData);
            session()->flash('message', 'Vendor created successfully.');
        }

        $this->showModal = false;
    }

    public function delete(int $id): void
    {
        $vendor = Vendor::findOrFail($id);
        
        if ($vendor->purchaseOrders()->count() > 0) {
            session()->flash('error', 'Cannot delete vendor. They are linked to existing purchase orders.');
            return;
        }

        $vendor->delete();
        session()->flash('message', 'Vendor deleted successfully.');
    }

    public function render()
    {
        $query = Vendor::query();

        if ($this->activeFilter === 'active') {
            $query->active();
        } elseif ($this->activeFilter === 'inactive') {
            $query->where('is_active', false);
        }

        if (!empty($this->search)) {
            $query->search($this->search);
        }

        $viewingVendor = null;
        $vendorHistory = collect();
        $vendorProducts = collect();

        if ($this->viewingVendorId) {
            $viewingVendor = Vendor::findOrFail($this->viewingVendorId);
            $vendorHistory = \App\Models\PurchaseOrder::where('vendor_id', $this->viewingVendorId)
                ->with('branch')
                ->latest()
                ->get();
            
            $vendorProducts = \App\Models\PurchaseOrderItem::whereHas('purchaseOrder', function($q) {
                    $q->where('vendor_id', $this->viewingVendorId);
                })
                ->with('product')
                ->select('product_id', \DB::raw('MAX(unit_cost) as max_price'), \DB::raw('MAX(created_at) as last_ordered'))
                ->groupBy('product_id')
                ->get();
        }

        return view('livewire.admin.vendor-index', [
            'vendors' => $query->latest()->paginate(10),
            'viewingVendor' => $viewingVendor,
            'vendorHistory' => $vendorHistory,
            'vendorProducts' => $vendorProducts,
        ])->layout('components.layouts.admin', ['title' => 'Suppliers & Vendors']);
    }
}
