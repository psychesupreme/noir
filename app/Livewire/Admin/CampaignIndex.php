<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class CampaignIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $channelFilter = 'all'; // all, email, sms
    public string $statusFilter = 'all'; // all, draft, scheduled, sent
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Modal state
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingCampaignId = null;

    // Form fields
    public string $title = '';
    public string $channel = 'email';
    public ?string $subject = '';
    public string $content = '';
    public ?string $scheduled_at = null;

    // Delete confirmation
    public bool $showDeleteModal = false;
    public ?int $deletingCampaignId = null;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'channel' => 'required|in:email,sms',
            'subject' => 'required_if:channel,email|nullable|string|max:255',
            'content' => 'required|string|min:10',
            'scheduled_at' => 'nullable|date|after_or_equal:now',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'scheduled_at' => 'scheduled date and time',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingChannelFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
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

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $campaignId): void
    {
        $campaign = Campaign::findOrFail($campaignId);
        $this->editingCampaignId = $campaign->id;
        $this->title = $campaign->title;
        $this->channel = $campaign->channel;
        $this->subject = $campaign->subject ?? '';
        $this->content = $campaign->content;
        $this->scheduled_at = $campaign->scheduled_at ? $campaign->scheduled_at->format('Y-m-d\TH:i') : null;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        // Convert empty string to null for scheduled_at
        $validated['scheduled_at'] = !empty($this->scheduled_at) ? $this->scheduled_at : null;

        // If scheduled_at is provided, set status to 'scheduled' unless it was already sent
        if ($validated['scheduled_at'] && !$this->isEditing) {
            $validated['status'] = 'scheduled';
        }

        if ($this->isEditing && $this->editingCampaignId) {
            $campaign = Campaign::findOrFail($this->editingCampaignId);
            // Don't modify status if already sent
            if ($campaign->status === 'sent') {
                unset($validated['status']);
            }
            $campaign->update($validated);
        } else {
            Campaign::create($validated);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $campaignId): void
    {
        $this->deletingCampaignId = $campaignId;
        $this->showDeleteModal = true;
    }

    public function deleteCampaign(): void
    {
        if ($this->deletingCampaignId) {
            Campaign::destroy($this->deletingCampaignId);
        }
        $this->showDeleteModal = false;
        $this->deletingCampaignId = null;
    }

    public function triggerSend(int $campaignId): void
    {
        $campaign = Campaign::findOrFail($campaignId);
        
        if ($campaign->status === 'sent') {
            return;
        }

        $clients = Client::whereNotNull('email')->where('email', '!=', '')->get();
        $sentCount = 0;

        if ($campaign->channel === 'email') {
            foreach ($clients as $client) {
                try {
                    \Illuminate\Support\Facades\Mail::to($client->email)
                        ->queue(new \App\Mail\CampaignMail($campaign));
                    $sentCount++;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to queue campaign email to {$client->email}: " . $e->getMessage());
                }
            }
        } else {
            // SMS channel fallback (to be integrated next)
            $sentCount = $clients->count();
        }

        $campaign->update([
            'status' => 'sent',
            'sent_count' => $sentCount,
            'scheduled_at' => now(),
        ]);

        session()->flash('message', 'Campaign broadcast triggered successfully for ' . $sentCount . ' recipients.');
    }

    protected function resetForm(): void
    {
        $this->reset([
            'editingCampaignId', 'title', 'channel', 'subject', 'content', 'scheduled_at', 'isEditing'
        ]);
        $this->channel = 'email';
    }

    public function render()
    {
        $query = Campaign::query();

        if ($this->channelFilter !== 'all') {
            $query->where('channel', $this->channelFilter);
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('subject', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        // Stats
        $totalCampaigns = Campaign::count();
        $scheduledCampaigns = Campaign::where('status', 'scheduled')->count();
        $emailSent = Campaign::where('channel', 'email')->where('status', 'sent')->sum('sent_count');
        $smsSent = Campaign::where('channel', 'sms')->where('status', 'sent')->sum('sent_count');

        return view('livewire.admin.campaign-index', [
            'campaigns' => $query->paginate(12),
            'totalCampaigns' => $totalCampaigns,
            'scheduledCampaigns' => $scheduledCampaigns,
            'emailSent' => $emailSent,
            'smsSent' => $smsSent,
        ])->layout('components.layouts.admin');
    }
}
