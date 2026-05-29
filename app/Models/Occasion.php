<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occasion extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'accent_color', 'is_major_holiday'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}