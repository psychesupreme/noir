<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ServicesGifts extends Component
{
    public function render()
    {
        // Retrieve specializations and gift accessories
        $services = Product::where('category', 'specializations')->get();
        $gifts = Product::whereIn('category', ['hampers', 'home_decor'])->get();

        return view('livewire.services-gifts', [
            'services' => $services,
            'gifts' => $gifts,
        ])->layout('components.layouts.app');
    }
}
