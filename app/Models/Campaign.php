<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'channel',
        'subject',
        'content',
        'scheduled_at',
        'status',
        'sent_count',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];
}
