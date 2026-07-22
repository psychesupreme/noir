<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\StorefrontCacheService;
use Livewire\Component;

class CurationBuilder extends Component
{
    use \App\Livewire\Traits\HasNotificationsAndTheme;

    // Selections state (Only state variables are serialized)
    public array $selectedStems = []; // [productId => quantity]
    public $selectedWrappingId = null;
    public bool $hasGlitter = false;
    public $selectedRibbonId = null;
    public $selectedMistId = null;
    public array $selectedGifts = []; // [productId => quantity]
    public bool $hasCard = false;
    public string $cardMessage = '';
    public int $activeStep = 1;
    public string $curationOccasion = 'birthday';
    public string $cardPrintPreference = 'handwritten';

    // Calculated fields
    public $subtotal = 0;

    // Theme variable synchronized with site
    public $theme = 'light';

    public function mount()
    {
        $stems = Product::where('category', 'stems')->pluck('id');
        foreach ($stems as $stemId) {
            $this->selectedStems[$stemId] = 0;
        }

        $wines = Product::where('category', 'giftings')->where('unit_type', 'bottle')->pluck('id');
        foreach ($wines as $wineId) {
            $this->selectedGifts[$wineId] = 0;
        }

        $chocolates = Product::where('category', 'giftings')->where('unit_type', 'box')->pluck('id');
        foreach ($chocolates as $chocId) {
            $this->selectedGifts[$chocId] = 0;
        }

        $jewelry = Product::where('category', 'giftings')->where('unit_type', 'jewelry')->pluck('id');
        foreach ($jewelry as $jewelId) {
            $this->selectedGifts[$jewelId] = 0;
        }

        $this->calculateSubtotal();
    }

    public function getAvailableStemsProperty()
    {
        return StorefrontCacheService::remember('curation_builder_stems', 600, fn() => Product::where('category', 'stems')->get());
    }

    public function getAvailableWrappingsProperty()
    {
        return StorefrontCacheService::remember('curation_builder_wrappings', 600, fn() => Product::where('unit_type', 'wrap')
            ->where('name', 'not like', '%Ribbon%')
            ->where('sku', '!=', 'NB-DEC-GLT-09')
            ->where('sku', '!=', 'NB-DEC-HLN-13')
            ->get());
    }

    public function getAvailableMistsProperty()
    {
        return StorefrontCacheService::remember('curation_builder_mists', 600, fn() => Product::where('category', 'bundle')->where('unit_type', 'bottle')->get());
    }

    public function getAvailableWinesProperty()
    {
        return StorefrontCacheService::remember('curation_builder_wines', 600, fn() => Product::where('category', 'giftings')->where('unit_type', 'bottle')->get());
    }

    public function getAvailableChocolatesProperty()
    {
        return StorefrontCacheService::remember('curation_builder_chocolates', 600, fn() => Product::where('category', 'giftings')->where('unit_type', 'box')->get());
    }

    public function getAvailableJewelryProperty()
    {
        return StorefrontCacheService::remember('curation_builder_jewelry', 600, fn() => Product::where('category', 'giftings')->where('unit_type', 'jewelry')->get());
    }

    public function getAvailableRibbonsProperty()
    {
        return StorefrontCacheService::remember('curation_builder_ribbons', 600, fn() => Product::where('unit_type', 'wrap')->where('name', 'like', '%Ribbon%')->get());
    }

    public function getGlitterProductProperty()
    {
        return StorefrontCacheService::remember('curation_glitter_product', 600, fn() => Product::where('sku', 'NB-DEC-GLT-09')->first());
    }

    public function getCardProductProperty()
    {
        return StorefrontCacheService::remember('curation_card_product', 600, fn() => Product::where('sku', 'NB-DEC-HLN-13')->first());
    }

    public function getHandCurationProductProperty()
    {
        $product = Product::firstOrCreate(
            ['sku' => 'NB-SRV-HCS-01'],
            [
                'name' => 'Atelier Hand Curation Service',
                'description' => 'Professional hand-curation by our master florists, ensuring structural balance, aesthetic perfection, and premium packaging assembly.',
                'price' => 150,
                'cost_price' => 50,
                'stock' => 9999,
                'category' => 'specialization',
                'unit_type' => 'arrangement',
                'grade' => 'Premium Service',
                'image_url' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&q=80&w=600',
            ]
        );

        if ($product->price === 1500) {
            $product->update(['price' => 150]);
        }

        return $product;
    }

    public function getCurationFeeProperty()
    {
        $stemCount = array_sum($this->selectedStems);
        return match (true) {
            $stemCount > 15 => 750,
            $stemCount > 5 => 350,
            default => 150,
        };
    }

    public function calculateSubtotal()
    {
        $total = 0;
        $stems = $this->availableStems;

        // Base Stems: price per stem * count
        foreach ($this->selectedStems as $stemId => $qty) {
            if ($qty > 0) {
                $stem = $stems->firstWhere('id', $stemId) ?? Product::find($stemId);
                if ($stem) {
                    $total += $stem->price * $qty;
                }
            }
        }

        // Wrapping
        if ($this->selectedWrappingId) {
            $wrap = $this->availableWrappings->firstWhere('id', $this->selectedWrappingId) ?? Product::find($this->selectedWrappingId);
            if ($wrap) {
                $total += $wrap->price;
            }
        }

        // Glitter
        if ($this->hasGlitter && $this->glitterProduct) {
            $total += $this->glitterProduct->price;
        }

        // Ribbon
        if ($this->selectedRibbonId) {
            $ribbon = $this->availableRibbons->firstWhere('id', $this->selectedRibbonId) ?? Product::find($this->selectedRibbonId);
            if ($ribbon) {
                $total += $ribbon->price;
            }
        }

        // Scent Mist
        if ($this->selectedMistId) {
            $mist = $this->availableMists->firstWhere('id', $this->selectedMistId) ?? Product::find($this->selectedMistId);
            if ($mist) {
                $total += $mist->price;
            }
        }

        // Gifts
        $allGifts = $this->availableWines->concat($this->availableChocolates)->concat($this->availableJewelry);
        foreach ($this->selectedGifts as $giftId => $qty) {
            if ($qty > 0) {
                $gift = $allGifts->firstWhere('id', $giftId) ?? Product::find($giftId);
                if ($gift) {
                    $total += $gift->price * $qty;
                }
            }
        }

        // Greeting Card
        if ($this->hasCard && $this->cardProduct) {
            $total += $this->cardProduct->price;
        }

        // Atelier Hand Curation Fee (Scaled: Small 150, Medium 350, Grand 750)
        $total += $this->curationFee;

        $this->subtotal = $total;
    }

    public function updateStemQuantity($stemId, $change)
    {
        $current = $this->selectedStems[$stemId] ?? 0;
        $stems = $this->availableStems;
        $stem = $stems->firstWhere('id', $stemId) ?? Product::find($stemId);

        if (!$stem) return;

        $newQty = max(0, $current + $change);

        if ($newQty > $stem->stock) {
            session()->flash('error', "Only {$stem->stock} stems available for {$stem->name}.");
            return;
        }

        $this->selectedStems[$stemId] = $newQty;
        $this->calculateSubtotal();
    }

    public function adjustStemQuantity($stemId, $change)
    {
        return $this->updateStemQuantity($stemId, $change);
    }

    public function addStem($stemId)
    {
        return $this->updateStemQuantity($stemId, 1);
    }

    public function removeStem($stemId)
    {
        return $this->updateStemQuantity($stemId, -1);
    }

    public function updateGiftQuantity($giftId, $change)
    {
        $current = $this->selectedGifts[$giftId] ?? 0;
        $gift = Product::find($giftId);

        if (!$gift) return;

        $newQty = max(0, $current + $change);

        if ($newQty > $gift->stock) {
            session()->flash('error', "Only {$gift->stock} items available for {$gift->name}.");
            return;
        }

        $this->selectedGifts[$giftId] = $newQty;
        $this->calculateSubtotal();
    }

    public function adjustGiftQuantity($giftId, $change)
    {
        return $this->updateGiftQuantity($giftId, $change);
    }

    public function selectWrapping($wrappingId)
    {
        $this->selectedWrappingId = ($this->selectedWrappingId === $wrappingId) ? null : $wrappingId;
        $this->calculateSubtotal();
    }

    public function selectRibbon($ribbonId)
    {
        $this->selectedRibbonId = ($this->selectedRibbonId === $ribbonId) ? null : $ribbonId;
        $this->calculateSubtotal();
    }

    public function selectMist($mistId)
    {
        $this->selectedMistId = ($this->selectedMistId === $mistId) ? null : $mistId;
        $this->calculateSubtotal();
    }

    public function toggleGlitter()
    {
        $this->hasGlitter = !$this->hasGlitter;
        $this->calculateSubtotal();
    }

    public function toggleCard()
    {
        $this->hasCard = !$this->hasCard;
        $this->calculateSubtotal();
    }

    public function resetCuration()
    {
        foreach ($this->selectedStems as $k => $v) {
            $this->selectedStems[$k] = 0;
        }
        foreach ($this->selectedGifts as $k => $v) {
            $this->selectedGifts[$k] = 0;
        }
        $this->selectedWrappingId = null;
        $this->hasGlitter = false;
        $this->selectedRibbonId = null;
        $this->selectedMistId = null;
        $this->hasCard = false;
        $this->cardMessage = '';
        $this->activeStep = 1;
        $this->calculateSubtotal();
    }

    public function addToCart()
    {
        $totalStemsCount = array_sum($this->selectedStems);

        if ($totalStemsCount === 0) {
            session()->flash('error', 'Please select at least 1 floral stem for your custom arrangement.');
            return;
        }

        $cart = session()->get('noir_bloom_cart', []);

        // Add selected stems
        foreach ($this->selectedStems as $stemId => $qty) {
            if ($qty > 0) {
                $stem = Product::find($stemId);
                if ($stem) {
                    if ($stem->stock < $qty) {
                        session()->flash('error', "Cannot add. Only {$stem->stock} stems available for {$stem->name}.");
                        return;
                    }
                    $cart[$stem->id . '-standard'] = ($cart[$stem->id . '-standard'] ?? 0) + $qty;
                }
            }
        }

        // Add selected wrapping
        if ($this->selectedWrappingId) {
            $wrap = Product::find($this->selectedWrappingId);
            if ($wrap) {
                $cart[$wrap->id . '-standard'] = ($cart[$wrap->id . '-standard'] ?? 0) + 1;
            }
        }

        // Add glitter product
        if ($this->hasGlitter && $this->glitterProduct) {
            $cart[$this->glitterProduct->id . '-standard'] = ($cart[$this->glitterProduct->id . '-standard'] ?? 0) + 1;
        }

        // Add selected ribbon
        if ($this->selectedRibbonId) {
            $ribbon = Product::find($this->selectedRibbonId);
            if ($ribbon) {
                $cart[$ribbon->id . '-standard'] = ($cart[$ribbon->id . '-standard'] ?? 0) + 1;
            }
        }

        // Add selected mist
        if ($this->selectedMistId) {
            $mist = Product::find($this->selectedMistId);
            if ($mist) {
                $cart[$mist->id . '-standard'] = ($cart[$mist->id . '-standard'] ?? 0) + 1;
            }
        }

        // Add selected gifts
        foreach ($this->selectedGifts as $giftId => $qty) {
            if ($qty > 0) {
                $gift = Product::find($giftId);
                if ($gift) {
                    if ($gift->stock < $qty) {
                        session()->flash('error', "Cannot add. Only {$gift->stock} items available for {$gift->name}.");
                        return;
                    }
                    $cart[$gift->id . '-standard'] = ($cart[$gift->id . '-standard'] ?? 0) + $qty;
                }
            }
        }

        // Add greeting card note product
        if ($this->hasCard && $this->cardProduct) {
            $cart[$this->cardProduct->id . '-standard'] = ($cart[$this->cardProduct->id . '-standard'] ?? 0) + 1;
        }

        // Add Hand Curation Service fee product (Mandatory, Locked)
        if ($this->handCurationProduct) {
            $cart[$this->handCurationProduct->id . '-standard'] = 1;
        }

        session()->put('noir_bloom_cart', $cart);

        // Store custom selections (like ribbon color and card message) in customizations session
        $ribbonName = 'None';
        if ($this->selectedRibbonId) {
            $ribbon = Product::find($this->selectedRibbonId);
            if ($ribbon) {
                $ribbonName = str_replace(' Ribbon', '', str_replace(' Satin', '', $ribbon->name));
            }
        }

        session()->put('noir_bloom_customizations', [
            'card_message' => $this->hasCard ? $this->cardMessage : null,
            'card_print_preference' => $this->hasCard ? $this->cardPrintPreference : null,
            'ribbon_color' => $ribbonName !== 'None' ? $ribbonName : null,
            'glitter' => $this->hasGlitter ? 'Yes' : 'No',
            'curation_occasion' => $this->curationOccasion
        ]);

        session()->put('open_curation_drawer_after_login', true);

        session()->flash('success', 'Your custom floral curation has been added to cart.');
        return redirect()->route('storefront');
    }

    public function render()
    {
        // Find active fragrance mist description
        $activeScent = 'Subtle Rose';
        if ($this->selectedMistId) {
            $mist = Product::find($this->selectedMistId);
            if ($mist) {
                $activeScent = str_replace('Atelier ', '', str_replace(' Mist', '', $mist->name));
            }
        }

        $cartCount = array_sum(session()->get('noir_bloom_cart', []));

        return view('livewire.curation-builder', [
            'availableStems' => $this->availableStems,
            'availableWrappings' => $this->availableWrappings,
            'availableMists' => $this->availableMists,
            'availableWines' => $this->availableWines,
            'availableChocolates' => $this->availableChocolates,
            'availableJewelry' => $this->availableJewelry,
            'availableRibbons' => $this->availableRibbons,
            'glitterProduct' => $this->glitterProduct,
            'cardProduct' => $this->cardProduct,
            'handCurationProduct' => $this->handCurationProduct,
            'subtotal' => $this->subtotal,
            'activeScent' => $activeScent,
            'cartCount' => $cartCount
        ])->layout('components.layouts.app', ['title' => 'Atelier Noir & Bloom | Curation Studio']);
    }
}
