<?php

namespace App\Livewire\Courier;

use App\Models\Order;
use App\Services\AfricasTalkingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class DeliveryHandover extends Component
{
    use WithFileUploads;

    public Order $order;
    public $photo;
    public string $courier_notes = '';
    public bool $isDelivered = false;
    public ?string $successMessage = null;

    public function mount(Order $order): void
    {
        $this->order = $order->load(['client', 'products', 'branch']);
        $this->courier_notes = $order->courier_notes ?? '';
        $this->isDelivered = $order->status === 'delivered';
    }

    public function markAsDelivered(): void
    {
        $rules = [
            'courier_notes' => 'nullable|string|max:1000',
        ];

        // Require photo if order is not already delivered and no photo uploaded yet
        if (!$this->isDelivered && !$this->order->pod_photo_path) {
            $rules['photo'] = 'required|image|max:10240'; // max 10MB
        } else {
            $rules['photo'] = 'nullable|image|max:10240';
        }

        $this->validate($rules);

        DB::transaction(function () {
            $photoPath = $this->order->pod_photo_path;

            if ($this->photo) {
                $photoPath = $this->photo->store('pod-photos', 'public');
            }

            $this->order->update([
                'status' => 'delivered',
                'pod_photo_path' => $photoPath,
                'delivered_at' => now(),
                'courier_notes' => $this->courier_notes,
            ]);

            // Trigger SMS delivery notification
            try {
                $smsService = app(AfricasTalkingService::class);
                $targetPhone = $this->order->is_gift && !empty($this->order->recipient_phone)
                    ? $this->order->recipient_phone
                    : ($this->order->client?->phone ?? null);

                if ($targetPhone) {
                    $orderNo = "NB-ORD-" . str_pad($this->order->id, 4, '0', STR_PAD_LEFT);
                    $smsService->sendSms(
                        $targetPhone,
                        "Noir & Bloom Atelier: Your luxury arrangement #{$orderNo} has been delivered safely! Rate your experience: " . route('profile-portal')
                    );
                }
            } catch (\Throwable $e) {
                Log::error("Failed to send PoD SMS for Order #{$this->order->id}: " . $e->getMessage());
            }
        });

        $this->order->refresh();
        $this->isDelivered = true;
        $this->successMessage = "Order #NB-ORD-" . str_pad($this->order->id, 4, '0', STR_PAD_LEFT) . " marked as DELIVERED successfully!";
    }

    public function render()
    {
        return view('livewire.courier.delivery-handover')
            ->layout('layouts.app', ['title' => 'Courier Handover — Order #NB-ORD-' . str_pad($this->order->id, 4, '0', STR_PAD_LEFT)]);
    }
}
