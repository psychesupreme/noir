<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ServicesGifts extends Component
{
    use \App\Livewire\Traits\HasNotificationsAndTheme;

    public ?int $quickViewProductId = null;
    public array $newReview = [
        'rating' => 5,
        'comment' => ''
    ];

    public function render()
    {
        $services = Product::whereIn('category', ['specializtion', 'specialization', 'specializations'])->get()->map(function($product) {
            $product->backdrop_url = $product->image_url ?: 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&q=80&w=600';
            $product->stock_standard = $product->stock;
            $product->stock_deluxe = (int) floor($product->stock * 0.7);
            $product->stock_grand = (int) floor($product->stock * 0.4);
            return $product;
        });

        $gifts = Product::whereIn('category', ['giftings', 'bundle', 'hampers', 'home_decor'])->get()->map(function($product) {
            $product->backdrop_url = $product->image_url ?: 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&q=80&w=600';
            $product->stock_standard = $product->stock;
            $product->stock_deluxe = (int) floor($product->stock * 0.7);
            $product->stock_grand = (int) floor($product->stock * 0.4);
            return $product;
        });
        
        // Count total items currently stored in the session-based shopping cart
        $cartCount = array_sum(session()->get('noir_bloom_cart', []));

        return view('livewire.services-gifts', [
            'services' => $services,
            'gifts' => $gifts,
            'cartCount' => $cartCount,
        ])->layout('components.layouts.app');
    }

    public function loadProductReviews(int $productId): void
    {
        $this->quickViewProductId = $productId;
        $this->resetReviewForm();
    }

    public function resetReviewForm(): void
    {
        $this->newReview = [
            'rating' => 5,
            'comment' => ''
        ];
    }

    public function submitProductReview(): void
    {
        $user = auth()->user();
        if (!$user) {
            session()->flash('error_review', 'Please log in to submit a review.');
            return;
        }

        $this->validate([
            'newReview.rating' => 'required|integer|min:1|max:5',
            'newReview.comment' => 'required|string|min:3|max:1000',
        ]);

        \App\Models\Review::create([
            'product_id' => $this->quickViewProductId,
            'user_id' => $user->id,
            'rating' => $this->newReview['rating'],
            'comment' => $this->newReview['comment'],
        ]);

        $this->resetReviewForm();
        session()->flash('success_review', 'Review submitted successfully!');
    }

    public function addToCuration(int $productId, string $size = 'standard'): void
    {
        $product = Product::find($productId);
        if (!$product) return;

        $stock = match($size) {
            'deluxe' => (int) floor($product->stock * 0.7),
            'grand' => (int) floor($product->stock * 0.4),
            default => $product->stock,
        };

        $cart = session()->get('noir_bloom_cart', []);
        $key = $productId . '-' . $size;
        $currentQty = $cart[$key] ?? 0;

        if ($currentQty >= $stock) {
            session()->flash('error_wishlist', 'Cannot add more of this size due to stock limits.');
            return;
        }

        $cart[$key] = $currentQty + 1;
        session()->put('noir_bloom_cart', $cart);

        session()->flash('success_wishlist', 'Added "' . $product->name . '" (' . ucfirst($size) . ') to your curation cart.');
    }

    public function toggleWishlist(int $productId): void
    {
        $user = auth()->user();
        if (!$user) {
            session()->flash('error_wishlist', 'Please log in to manage your wishlist.');
            return;
        }

        $settings = $user->settings ?? [];
        $wishlist = $settings['wishlist'] ?? [];

        if (in_array($productId, $wishlist)) {
            $wishlist = array_values(array_filter($wishlist, fn($id) => $id != $productId));
            $msg = 'Item removed from wishlist.';
        } else {
            $wishlist[] = $productId;
            $msg = 'Item added to wishlist.';
        }

        $settings['wishlist'] = $wishlist;
        $user->update(['settings' => $settings]);

        $this->dispatch('wishlist-updated', wishlistIds: $wishlist);
        session()->flash('success_wishlist', $msg);
    }
}
