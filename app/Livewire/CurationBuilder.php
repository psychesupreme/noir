<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class CurationBuilder extends Component
{
    public $availableStems;
    public $availableVases;
    public $availableWrappings;
    public $availableWines;
    public $availableGifts;
    public $availableFragrances;

    // Curation state
    public $selectedVaseId = null;
    public $selectedStems = []; // [productId => quantity]
    public $size = 'standard';
    public $theme = 'onyx';

    // Addons state
    public $selectedWrappingId = null;
    public $selectedWineId = null;
    public $selectedGiftId = null;
    public $selectedFragranceId = null;

    public $subtotal = 0;

    public function mount()
    {
        $this->availableStems = Product::where('category', 'stems')->get();
        $this->availableVases = Product::where('category', 'bundle')
            ->where('name', 'like', '%Vase%')
            ->get();

        // Fallback if no products match "Vase" filter
        if ($this->availableVases->isEmpty()) {
            $this->availableVases = Product::where('category', 'bundle')->get();
        }

        // Set default vase if available
        if ($this->availableVases->isNotEmpty()) {
            $this->selectedVaseId = $this->availableVases->first()->id;
        }

        // Query Addons
        $this->availableWrappings = Product::where('unit_type', 'wrap')->get();
        $this->availableWines = Product::where('category', 'giftings')
            ->where('unit_type', 'bottle')
            ->get();
        $this->availableGifts = Product::where(function($q) {
            $q->where('unit_type', 'box')->orWhere('sku', 'NB-DEC-TAC-02');
        })->get();
        $this->availableFragrances = Product::where('category', 'bundle')
            ->where('name', 'like', '%Mist%')
            ->get();

        // Initialize quantities
        foreach ($this->availableStems as $stem) {
            $this->selectedStems[$stem->id] = 0;
        }
    }

    public function selectVase($vaseId)
    {
        $this->selectedVaseId = $vaseId;
        $this->dispatch('vase-changed', $vaseId);
    }

    public function addStem($productId)
    {
        $product = Product::find($productId);
        if (!$product) return;

        $currentQty = $this->selectedStems[$productId] ?? 0;
        if ($currentQty >= $product->stock) {
            session()->flash('error', 'Cannot add more stems due to stock limits.');
            return;
        }

        $this->selectedStems[$productId] = $currentQty + 1;
        $this->dispatch('stem-added', [
            'productId' => $productId,
            'color' => $this->getFlowerColor($product->name),
            'type' => $this->getFlowerType($product->name),
            'qty' => $this->selectedStems[$productId]
        ]);
    }

    public function removeStem($productId)
    {
        $currentQty = $this->selectedStems[$productId] ?? 0;
        if ($currentQty > 0) {
            $this->selectedStems[$productId] = $currentQty - 1;
            $this->dispatch('stem-removed', [
                'productId' => $productId,
                'qty' => $this->selectedStems[$productId]
            ]);
        }
    }

    public function setSize($size)
    {
        $this->size = $size;
        $this->dispatch('size-changed', $size);
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;
        $this->dispatch('theme-changed', $theme);
    }

    public function selectWrapping($wrappingId)
    {
        $this->selectedWrappingId = $wrappingId;
    }

    public function selectWine($wineId)
    {
        $this->selectedWineId = $wineId;
    }

    public function selectGift($giftId)
    {
        $this->selectedGiftId = $giftId;
    }

    public function selectFragrance($fragranceId)
    {
        $this->selectedFragranceId = $fragranceId;
    }

    protected function sanitizeId($id)
    {
        if (is_iterable($id)) {
            return collect($id)->first();
        }
        return $id;
    }

    /**
     * Add the constructed curation bundle and optional accessories to the user's session cart.
     * Validates that all selected IDs correspond to valid products in their expected categories
     * to prevent parameter tampering/injection.
     */
    public function addToCart()
    {
        $cart = session()->get('noir_bloom_cart', []);

        $vaseId = $this->sanitizeId($this->selectedVaseId);
        $wrappingId = $this->sanitizeId($this->selectedWrappingId);
        $wineId = $this->sanitizeId($this->selectedWineId);
        $giftId = $this->sanitizeId($this->selectedGiftId);
        $fragranceId = $this->sanitizeId($this->selectedFragranceId);

        // --- BACKEND INPUT VALIDATION ---
        // Verify that the vase exists in the seeded vases collection
        if ($vaseId) {
            $vase = $this->availableVases->firstWhere('id', $vaseId);
            if (!$vase) {
                session()->flash('error', 'Selected vase is invalid or discontinued.');
                return;
            }
        }

        // Verify that the wrapping paper exists in the wrappings collection
        if ($wrappingId) {
            $wrapping = $this->availableWrappings->firstWhere('id', $wrappingId);
            if (!$wrapping) {
                session()->flash('error', 'Selected wrapping option is invalid.');
                return;
            }
        }

        // Verify that the wine exists in the wines collection
        if ($wineId) {
            $wine = $this->availableWines->firstWhere('id', $wineId);
            if (!$wine) {
                session()->flash('error', 'Selected wine or beverage is invalid.');
                return;
            }
        }

        // Verify that the gift item exists in the gifts collection
        if ($giftId) {
            $gift = $this->availableGifts->firstWhere('id', $giftId);
            if (!$gift) {
                session()->flash('error', 'Selected gift accessory is invalid.');
                return;
            }
        }

        // Verify that the fragrance exists in the fragrances collection
        if ($fragranceId) {
            $fragrance = $this->availableFragrances->firstWhere('id', $fragranceId);
            if (!$fragrance) {
                session()->flash('error', 'Selected floral fragrance is invalid.');
                return;
            }
        }

        // Add Base Vase to the cart array
        if ($vaseId) {
            $key = $vaseId . '-standard';
            $cart[$key] = ($cart[$key] ?? 0) + 1;
        }

        // Add Stems with size category validation
        $hasItems = false;
        foreach ($this->selectedStems as $stemId => $qty) {
            if ($qty > 0) {
                // Ensure the stem product ID actually matches a valid stem variety
                $stem = $this->availableStems->firstWhere('id', $stemId);
                if (!$stem) {
                    session()->flash('error', 'One or more selected stem varieties are invalid.');
                    return;
                }
                $key = $stemId . '-' . $this->size;
                $cart[$key] = ($cart[$key] ?? 0) + $qty;
                $hasItems = true;
            }
        }

        if (!$hasItems) {
            session()->flash('error', 'Please add at least one stem to your arrangement.');
            return;
        }

        // Add Wrapping Addon to the cart array
        if ($wrappingId) {
            $key = $wrappingId . '-standard';
            $cart[$key] = ($cart[$key] ?? 0) + 1;
        }

        // Add Wine Addon to the cart array
        if ($wineId) {
            $key = $wineId . '-standard';
            $cart[$key] = ($cart[$key] ?? 0) + 1;
        }

        // Add Gift Addon to the cart array
        if ($giftId) {
            $key = $giftId . '-standard';
            $cart[$key] = ($cart[$key] ?? 0) + 1;
        }

        // Add Fragrance Addon to the cart array
        if ($fragranceId) {
            $key = $fragranceId . '-standard';
            $cart[$key] = ($cart[$key] ?? 0) + 1;
        }

        // Persist back to session cart registries
        session()->put('noir_bloom_cart', $cart);
        session()->put('open_curation_drawer_after_login', true);

        session()->flash('success', 'Custom arrangement added to curation cart.');
        return redirect()->route('storefront');
    }

    /**
     * Compute subtotal using in-memory loaded collections to avoid N+1 database queries.
     */
    public function calculateSubtotal()
    {
        $total = 0;
        
        $vaseId = $this->sanitizeId($this->selectedVaseId);
        $wrappingId = $this->sanitizeId($this->selectedWrappingId);
        $wineId = $this->sanitizeId($this->selectedWineId);
        $giftId = $this->sanitizeId($this->selectedGiftId);
        $fragranceId = $this->sanitizeId($this->selectedFragranceId);

        // 1. Calculate Base Vase price
        if ($vaseId && $this->availableVases) {
            $vase = $this->availableVases->firstWhere('id', $vaseId);
            if ($vase) {
                $total += $vase->price;
            }
        }

        // 2. Calculate Stems price with size multiplier
        $multiplier = match($this->size) {
            'deluxe' => 1.5,
            'grand' => 2.2,
            default => 1.0
        };

        foreach ($this->selectedStems as $stemId => $qty) {
            if ($qty > 0 && $this->availableStems) {
                $product = $this->availableStems->firstWhere('id', $stemId);
                if ($product) {
                    $total += (int) round($product->price * $multiplier) * $qty;
                }
            }
        }

        // 3. Addon Wrapping price
        if ($wrappingId && $this->availableWrappings) {
            $wrapping = $this->availableWrappings->firstWhere('id', $wrappingId);
            if ($wrapping) {
                $total += $wrapping->price;
            }
        }

        // 4. Addon Wine price
        if ($wineId && $this->availableWines) {
            $wine = $this->availableWines->firstWhere('id', $wineId);
            if ($wine) {
                $total += $wine->price;
            }
        }

        // 5. Addon Gift price
        if ($giftId && $this->availableGifts) {
            $gift = $this->availableGifts->firstWhere('id', $giftId);
            if ($gift) {
                $total += $gift->price;
            }
        }

        // 6. Addon Fragrance price
        if ($fragranceId && $this->availableFragrances) {
            $fragrance = $this->availableFragrances->firstWhere('id', $fragranceId);
            if ($fragrance) {
                $total += $fragrance->price;
            }
        }

        return $total;
    }


    protected function getFlowerColor($name)
    {
        $nameLower = strtolower($name);
        if (str_contains($nameLower, 'red') || str_contains($nameLower, 'rose')) {
            return '#DC2626'; // Deep Red
        } elseif (str_contains($nameLower, 'white') || str_contains($nameLower, 'lily')) {
            return '#FFFFFF'; // White
        } elseif (str_contains($nameLower, 'coral') || str_contains($nameLower, 'hibiscus')) {
            return '#F97316'; // Coral Orange
        } elseif (str_contains($nameLower, 'lavender')) {
            return '#A78BFA'; // Purple
        } elseif (str_contains($nameLower, 'eucalyptus') || str_contains($nameLower, 'leaf') || str_contains($nameLower, 'foliage')) {
            return '#10B981'; // Green
        }
        return '#F59E0B'; // Gold fallback
    }

    protected function getFlowerType($name)
    {
        $nameLower = strtolower($name);
        if (str_contains($nameLower, 'rose')) {
            return 'rose';
        } elseif (str_contains($nameLower, 'lily')) {
            return 'lily';
        } elseif (str_contains($nameLower, 'hibiscus')) {
            return 'hibiscus';
        }
        return 'foliage';
    }

    public function render()
    {
        $this->subtotal = $this->calculateSubtotal();
        return view('livewire.curation-builder', [
            'subtotal' => $this->subtotal
        ])->layout('components.layouts.app');
    }
}
