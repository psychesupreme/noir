<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin     = 'admin';
    case Staff     = 'staff';
    case Wholesale = 'wholesale';
    case Retail    = 'retail';
    case Corporate = 'corporate';
    case Partners  = 'partners';

    /**
     * Human-readable label for this role.
     */
    public function label(): string
    {
        return match ($this) {
            self::Admin     => 'System Administrator',
            self::Staff     => 'Internal Staff',
            self::Wholesale => 'Wholesale Client',
            self::Retail    => 'Retail Customer',
            self::Corporate => 'Corporate Account',
            self::Partners  => 'Strategic Partner',
        };
    }

    /**
     * Check if this role is the top-level administrator.
     */
    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }

    /**
     * Check if this role has internal staff-level access.
     */
    public function isStaff(): bool
    {
        return in_array($this, [self::Admin, self::Staff], true);
    }

    /**
     * Check if this role represents a customer tier.
     */
    public function isCustomer(): bool
    {
        return in_array($this, [self::Retail, self::Wholesale, self::Corporate], true);
    }
}
