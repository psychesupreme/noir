<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'phone_number', 'account_tier', 'kra_pin', 'default_delivery_address', 'default_region'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'account_tier' => UserRole::class,
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Role Helpers — delegate to the UserRole enum                      */
    /* ------------------------------------------------------------------ */

    public function isAdmin(): bool
    {
        return $this->account_tier->isAdmin();
    }

    public function isStaff(): bool
    {
        return $this->account_tier->isStaff();
    }

    public function isCustomer(): bool
    {
        return $this->account_tier->isCustomer();
    }

    /**
     * Check if this user holds any of the given roles.
     */
    public function hasRole(UserRole ...$roles): bool
    {
        return in_array($this->account_tier, $roles, true);
    }

    /* ------------------------------------------------------------------ */
    /*  Accessors                                                         */
    /* ------------------------------------------------------------------ */

    /**
     * Human-readable account tier label (e.g. "System Administrator").
     */
    public function getDisplayTierAttribute(): string
    {
        return $this->account_tier->label();
    }

    /**
     * Loyalty tier label based on loyalty points.
     */
    public function getLoyaltyTierAttribute(): string
    {
        $points = $this->loyalty_points ?? 0;
        if ($points >= 5000) {
            return 'Noir';
        } elseif ($points >= 1000) {
            return 'Atelier';
        }
        return 'Bloom';
    }
}

