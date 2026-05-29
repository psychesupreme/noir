<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'location_city', 'is_active'];

    /**
     * Get all corporate orders assigned to this fulfillment branch hub node.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}