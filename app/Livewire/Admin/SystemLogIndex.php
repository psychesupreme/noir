<?php

namespace App\Livewire\Admin;

use App\Models\SystemLog;
use Livewire\Component;
use Livewire\WithPagination;

class SystemLogIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $levelFilter = 'all';
    public string $categoryFilter = 'all';

    public function mount(): void
    {
        abort_unless(auth()->user() && auth()->user()->isAdmin(), 403, 'Unauthorized: Admin system logs are restricted.');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingLevelFilter(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function clearLogs(): void
    {
        SystemLog::truncate();
        SystemLog::write('warning', 'system', 'Admin cleared all system logs from database.');
        session()->flash('success', 'System log history truncated successfully.');
    }

    public function render()
    {
        $query = SystemLog::query()->latest();

        if ($this->levelFilter !== 'all') {
            $query->where('level', $this->levelFilter);
        }

        if ($this->categoryFilter !== 'all') {
            $query->where('category', $this->categoryFilter);
        }

        if (trim($this->search) !== '') {
            $query->where(function ($q) {
                $q->where('message', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $this->search . '%')
                  ->orWhere('level', 'like', '%' . $this->search . '%');
            });
        }

        $logs = $query->paginate(25);

        return view('livewire.admin.system-log-index', [
            'logs' => $logs,
        ])->layout('components.layouts.admin', ['title' => 'Noir & Bloom | System Logs']);
    }
}
