<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'branch_id',
        'is_gift',
        'recipient_name',
        'recipient_phone',
        'total_amount',
        'service_fee_amount',
        'status',
        'special_instructions',
        'required_delivery_at',
        'rating',
        'feedback',
        'product_rating',
        'packaging_rating',
        'delivery_rating',
    ];

    protected $casts = [
        'is_gift' => 'boolean',
        'total_amount' => 'integer',
        'service_fee_amount' => 'integer',
    ];

    protected static function booted()
    {
        static::updated(function ($order) {
            if ($order->isDirty('status')) {
                $oldStatus = $order->getOriginal('status');
                $newStatus = $order->status;

                // Write system log
                \App\Models\SystemLog::write('info', 'order', "Order #NB-ORD-{$order->id} status updated from {$oldStatus} to {$newStatus}.", [
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                ]);

                // Create user notification
                if ($order->client && $order->client->user_id) {
                    $title = 'Order Status Update';
                    $message = "Your order #NB-ORD-{$order->id} status has been updated to " . strtoupper($newStatus) . ".";
                    
                    switch($newStatus) {
                        case 'pending':
                            $title = 'Order Placed';
                            $message = "We have received your order #NB-ORD-{$order->id} and it is currently awaiting payment. Click to complete authorization.";
                            break;
                        case 'approved':
                            $title = 'Curation Approved';
                            $message = "Exquisite choice! Your order #NB-ORD-{$order->id} is approved. Our Master Florists are now hand-selecting your fresh Rift Valley stems.";
                            break;
                        case 'processing':
                            $title = 'Atelier Design in Progress';
                            $message = "Your curation #NB-ORD-{$order->id} is in full bloom. Our concierge team is crafting the floral composition and custom lettering.";
                            break;
                        case 'shipping':
                            $title = 'Dispatch En Route';
                            $message = "Our luxury courier is en route with your curation #NB-ORD-{$order->id}, maintaining strict climate controls for absolute freshness.";
                            break;
                        case 'delivered':
                            $title = 'Delivered with Elegance';
                            $message = "Delivered. Your curation #NB-ORD-{$order->id} has been hand-delivered. We hope it brings profound delight.";
                            break;
                        case 'cancelled':
                            $title = 'Order Cancelled';
                            $message = "Your order #NB-ORD-{$order->id} has been cancelled. Please contact our Concierge Desk if you require any assistance.";
                            break;
                    }

                    \App\Models\Notification::create([
                        'user_id' => $order->client->user_id,
                        'title' => $title,
                        'message' => $message,
                        'type' => 'order',
                    ]);
                }
            }
        });

        static::created(function ($order) {
            // Write system log
            \App\Models\SystemLog::write('info', 'order', "Order #NB-ORD-{$order->id} created with total amount " . number_format($order->total_amount) . " KSH.", [
                'order_id' => $order->id,
                'total_amount' => $order->total_amount,
            ]);

            // Create user notification
            if ($order->client && $order->client->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $order->client->user_id,
                    'title' => 'Order Placed Successfully',
                    'message' => "Your order #NB-ORD-{$order->id} for " . number_format($order->total_amount) . " KSH has been placed.",
                    'type' => 'order',
                ]);
            }
        });

        static::saved(function ($order) {
            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');
        });

        static::deleted(function ($order) {
            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price_at_sale', 'cost_price_at_sale', 'size')->withTimestamps();
    }
    /**
     * Get all payment records and STK push requests logged against this order.
     */
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }
    /**
    * The eTIMS compliant tax invoice associated with this corporate transaction ledger.
    */
    public function etimsInvoice(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(EtimsInvoice::class);
    }
    /**
     * Get the specific physical atelier node responsible for fulfilling this order.
     */
    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Approximate road routing distance and time from branch hub to client coordinates via OSRM.
     */
    public function getRouteDetails(): ?array
    {
        $address = $this->client->delivery_address ?? '';
        if (!preg_match('/(-?\d+\.\d+),\s*(-?\d+\.\d+)/', $address, $matches)) {
            return null;
        }

        $clientLat = (float) $matches[1];
        $clientLng = (float) $matches[2];

        $isKiambu = false;
        if ($this->branch && strtolower($this->branch->location_city) === 'kiambu') {
            $isKiambu = true;
        } elseif ($this->client && strtolower($this->client->region) === 'kiambu') {
            $isKiambu = true;
        }

        if ($isKiambu) {
            $hubLat = -1.1444;
            $hubLng = 36.6853;
            $hubName = 'Kiambu Ridge Hub (Tigoni)';
        } else {
            $hubLat = -1.286389;
            $hubLng = 36.817222;
            $hubName = 'Nairobi Central Atelier (CBD)';
        }

        $cacheKey = "order_route_{$this->id}_" . md5("{$hubLat},{$hubLng};{$clientLat},{$clientLng}");

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addDays(7), function () use ($hubLat, $hubLng, $clientLat, $clientLng, $hubName) {
            try {
                $url = "http://router.project-osrm.org/route/v1/driving/{$hubLng},{$hubLat};{$clientLng},{$clientLat}?overview=false";
                $response = \Illuminate\Support\Facades\Http::timeout(5)->get($url);
                if ($response->successful()) {
                    $data = $response->json();
                    if (!empty($data['routes'][0])) {
                        $route = $data['routes'][0];
                        $distanceMeters = $route['distance'] ?? 0;
                        $durationSeconds = $route['duration'] ?? 0;

                        return [
                            'distance_km' => round($distanceMeters / 1000, 1),
                            'duration_min' => (int) round($durationSeconds / 60),
                            'hub_name' => $hubName,
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Ignore and fall back
            }

            // Fallback estimation (Haversine + 40km/h average speed)
            $theta = $hubLng - $clientLng;
            $dist = sin(deg2rad($hubLat)) * sin(deg2rad($clientLat)) +  cos(deg2rad($hubLat)) * cos(deg2rad($clientLat)) * cos(deg2rad($theta));
            $dist = min(1.0, max(-1.0, $dist));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $km = round($miles * 1.609344, 1);
            $durationMin = (int) round(($km / 40) * 60);

            return [
                'distance_km' => $km,
                'duration_min' => max(5, $durationMin),
                'hub_name' => $hubName,
                'is_fallback' => true,
            ];
        });
    }

}