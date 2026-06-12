<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ServicesGifts extends Component
{
    /**
     * Render the Bespoke Services and Gifting Suite view.
     * Computes active cart count from session cart to render unified header count badges.
     */
    public function render()
    {
        $services = Product::whereIn('category', ['specializtion', 'specialization', 'specializations'])->get();
        $gifts = Product::whereIn('category', ['giftings', 'bundle', 'hampers', 'home_decor'])->get();
        
        // Count total items currently stored in the session-based shopping cart
        $cartCount = array_sum(session()->get('noir_bloom_cart', []));

        return view('livewire.services-gifts', [
            'services' => $services,
            'gifts' => $gifts,
            'cartCount' => $cartCount,
        ])->layout('components.layouts.app');
    }

}
