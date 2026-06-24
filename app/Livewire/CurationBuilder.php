<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class CurationBuilder extends Component
{
    use \App\Livewire\Traits\HasNotificationsAndTheme;

    // Database catalogs
    public $availableStems;
    public $availableWrappings;
    public $availableMists;
    public $availableWines;
    public $availableChocolates;
    public $availableJewelry;
    public $availableRibbons;
    public $glitterProduct;
    public $cardProduct;
    public $handCurationProduct;

    // Selections state
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
        $this->availableStems = Product::where('category', 'stems')->get();
        $this->availableWrappings = Product::where('unit_type', 'wrap')
            ->where('name', 'not like', '%Ribbon%')
            ->where('sku', '!=', 'NB-DEC-GLT-09')
            ->where('sku', '!=', 'NB-DEC-HLN-13')
            ->get();
        $this->availableMists = Product::where('category', 'bundle')->where('unit_type', 'bottle')->get();
        $this->availableWines = Product::where('category', 'giftings')->where('unit_type', 'bottle')->get();
        $this->availableChocolates = Product::where('category', 'giftings')->where('unit_type', 'box')->get();
        $this->availableJewelry = Product::where('category', 'giftings')->where('unit_type', 'jewelry')->get();
        $this->availableRibbons = Product::where('unit_type', 'wrap')->where('name', 'like', '%Ribbon%')->get();
        $this->glitterProduct = Product::where('sku', 'NB-DEC-GLT-09')->first();
        $this->cardProduct = Product::where('sku', 'NB-DEC-HLN-13')->first();
        $this->handCurationProduct = Product::firstOrCreate(
            ['sku' => 'NB-SRV-HCS-01'],
            [
                'name' => 'Atelier Hand Curation Service',
                'description' => 'Professional hand-curation by our master florists, ensuring structural balance, aesthetic perfection, and premium packaging assembly.',
                'price' => 1500,
                'cost_price' => 200,
                'stock' => 9999,
                'category' => 'specialization',
                'unit_type' => 'arrangement',
                'grade' => 'Premium Service',
                'image_url' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&q=80&w=600',
            ]
        );

        // Initialize stems with quantity 0
        foreach ($this->availableStems as $stem) {
            $this->selectedStems[$stem->id] = 0;
        }

        // Initialize gifts with quantity 0
        foreach ($this->availableWines as $wine) {
            $this->selectedGifts[$wine->id] = 0;
        }
        foreach ($this->availableChocolates as $choc) {
            $this->selectedGifts[$choc->id] = 0;
        }
        foreach ($this->availableJewelry as $jewel) {
            $this->selectedGifts[$jewel->id] = 0;
        }

        $this->calculateSubtotal();
    }

    public function calculateSubtotal()
    {
        $total = 0;

        // Base Stems: price per stem * count
        foreach ($this->selectedStems as $stemId => $qty) {
            if ($qty > 0) {
                $stem = Product::find($stemId);
                if ($stem) {
                    $total += $stem->price * $qty;
                }
            }
        }

        // Wrapping
        if ($this->selectedWrappingId) {
            $wrap = Product::find($this->selectedWrappingId);
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
            $ribbon = Product::find($this->selectedRibbonId);
            if ($ribbon) {
                $total += $ribbon->price;
            }
        }

        // Fragrance Mist
        if ($this->selectedMistId) {
            $mist = Product::find($this->selectedMistId);
            if ($mist) {
                $total += $mist->price;
            }
        }

        // Selected Gifts
        foreach ($this->selectedGifts as $giftId => $qty) {
            if ($qty > 0) {
                $gift = Product::find($giftId);
                if ($gift) {
                    $total += $gift->price * $qty;
                }
            }
        }

        // Handwritten Card
        if ($this->hasCard && $this->cardProduct) {
            $total += $this->cardProduct->price;
        }

        // Mandatory Atelier Hand Curation Service Fee (Locked)
        if ($this->handCurationProduct) {
            $total += $this->handCurationProduct->price;
        } else {
            $total += 1500;
        }

        $this->subtotal = $total;
    }

    public function adjustStemQuantity($stemId, $delta)
    {
        $qty = $this->selectedStems[$stemId] ?? 0;
        $newQty = max(0, $qty + $delta);

        if ($newQty > 0) {
            $stem = Product::find($stemId);
            if ($stem && $stem->stock < $newQty) {
                $this->dispatch('curation-error', "Only {$stem->stock} stems available in stock.");
                return;
            }
            $this->selectedStems[$stemId] = $newQty;
        } else {
            $this->selectedStems[$stemId] = 0;
        }
        $this->calculateSubtotal();
        $this->dispatch('stems-updated');
    }

    public function selectWrapping($wrappingId)
    {
        $wrap = Product::find($wrappingId);
        if ($wrap && $wrap->stock <= 0) {
            $this->dispatch('curation-error', "Wrapping '" . $wrap->name . "' is out of stock.");
            return;
        }
        $this->selectedWrappingId = $wrappingId;
        $this->calculateSubtotal();
        $this->dispatch('wrapping-changed', $wrappingId);
    }

    public function toggleGlitter()
    {
        if (!$this->hasGlitter && $this->glitterProduct && $this->glitterProduct->stock <= 0) {
            $this->dispatch('curation-error', "Glitter Accent is out of stock.");
            return;
        }
        $this->hasGlitter = !$this->hasGlitter;
        $this->calculateSubtotal();
        $this->dispatch('glitter-toggled', $this->hasGlitter);
    }

    public function selectRibbon($ribbonId)
    {
        if ($ribbonId) {
            $ribbon = Product::find($ribbonId);
            if ($ribbon && $ribbon->stock <= 0) {
                $this->dispatch('curation-error', "Ribbon '" . $ribbon->name . "' is out of stock.");
                return;
            }
        }
        $this->selectedRibbonId = ($this->selectedRibbonId == $ribbonId) ? null : $ribbonId;
        $this->calculateSubtotal();
        $this->dispatch('ribbon-changed', $this->selectedRibbonId);
    }

    public function selectMist($mistId)
    {
        if ($mistId) {
            $mist = Product::find($mistId);
            if ($mist && $mist->stock <= 0) {
                $this->dispatch('curation-error', "Mist '" . $mist->name . "' is out of stock.");
                return;
            }
        }
        $this->selectedMistId = ($this->selectedMistId == $mistId) ? null : $mistId;
        $this->calculateSubtotal();
        $this->dispatch('mist-changed', $this->selectedMistId);
    }

    public function adjustGiftQuantity($giftId, $delta)
    {
        $qty = $this->selectedGifts[$giftId] ?? 0;
        $newQty = max(0, $qty + $delta);

        if ($newQty > 0) {
            $gift = Product::find($giftId);
            if ($gift && $gift->stock < $newQty) {
                $this->dispatch('curation-error', "Only " . $gift->stock . " items available in stock.");
                return;
            }
            $this->selectedGifts[$giftId] = $newQty;
        } else {
            $this->selectedGifts[$giftId] = 0;
        }
        $this->calculateSubtotal();
        $this->dispatch('gifts-updated');
    }

    public function toggleCard()
    {
        if (!$this->hasCard && $this->cardProduct && $this->cardProduct->stock <= 0) {
            $this->dispatch('curation-error', "Greeting Card is out of stock.");
            return;
        }
        $this->hasCard = !$this->hasCard;
        if (!$this->hasCard) {
            $this->cardMessage = '';
        }
        $this->calculateSubtotal();
        $this->dispatch('card-toggled', $this->hasCard);
    }

    public function addToCart()
    {
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

        // Add glitter
        if ($this->hasGlitter && $this->glitterProduct) {
            $cart[$this->glitterProduct->id . '-standard'] = ($cart[$this->glitterProduct->id . '-standard'] ?? 0) + 1;
        }

        // Add ribbon
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
            'subtotal' => $this->subtotal,
            'activeScent' => $activeScent,
            'cartCount' => $cartCount
        ])->layout('components.layouts.app');
    }
}
