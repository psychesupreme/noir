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
        'required_delivery_at'
    ];

    protected $casts = [
        'is_gift' => 'boolean',
        'total_amount' => 'integer',
        'service_fee_amount' => 'integer',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price_at_sale')->withTimestamps();
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

    protected static function booted(): void
    {
        static::saved(function ($order) {
            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');
        });

        static::deleted(function ($order) {
            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');
        });
    }
}