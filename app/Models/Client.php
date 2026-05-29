<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['company_name', 'kra_pin', 'contact_name', 'email', 'phone', 'region', 'delivery_address'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Total revenue from this client (excluding cancelled orders).
     */
    public function getTotalSpentAttribute(): float
    {
        return (float) $this->orders()
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
    }

    /**
     * Count of all orders placed by this client.
     */
    public function getOrderCountAttribute(): int
    {
        return (int) $this->orders()->count();
    }

    /**
     * Date of the client's most recent order.
     */
    public function getLastOrderDateAttribute(): ?string
    {
        $latest = $this->orders()->latest()->first();

        return $latest?->created_at?->toDateTimeString();
    }

    /**
     * Client classification: Corporate (has KRA PIN) or Individual.
     */
    public function getTypeAttribute(): string
    {
        return !empty($this->kra_pin) ? 'Corporate' : 'Individual';
    }
}