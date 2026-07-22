<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $fillable = [
        'level',
        'category',
        'message',
        'context',
        'user_id',
        'ip_address',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    /**
     * Write a system log record.
     */
    public static function write(string $level, string $category, string $message, ?array $context = null): self
    {
        return self::create([
            'level' => $level,
            'category' => $category,
            'message' => $message,
            'context' => $context,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
        ]);
    }
}
