<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ServicesGifts extends Component
{
    public function render()
    {
        $services = Product::whereIn('category', ['specializtion', 'specialization', 'specializations'])->get();
        $gifts = Product::whereIn('category', ['giftings', 'bundle', 'hampers', 'home_decor'])->get();

        return view('livewire.services-gifts', [
            'services' => $services,
            'gifts' => $gifts,
        ])->layout('components.layouts.app');
    }
}
