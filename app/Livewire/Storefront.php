<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\Client;
use App\Models\Occasion;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Services\MpesaService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Exception;

class Storefront extends Component
{
    use \App\Livewire\Traits\HasNotificationsAndTheme;


    #[Url(as: 'find', history: true)]
    public string $search = '';
    
    #[Url(as: 'tier', history: true)]
    public string $selectedCategory = 'all'; 

    #[Url(as: 'sort', history: true)]
    public string $sortBy = 'latest';


    public array $cart = [];
    public array $slides = [];

    // FNP Delivery details
    public string $deliveryCity = '';
    public string $deliveryDate = '';
    public string $deliverySlot = 'standard';
    public bool $deliveryDetailsValid = false;

    // Form inputs pre-filled from our active user session
    public string $full_name = '';
    public string $company_name = '';
    public string $kra_pin = '';
    public string $email = '';
    public string $phone = '';
    public string $region = 'Nairobi';
    public string $delivery_address = '';

    // Gifting Parameters
    public bool $is_gift = false;
    public string $recipient_name = '';
    public string $recipient_phone = '';

    // Delivery upsells
    public string $delivery_type = 'standard'; 
    public int $service_fee = 0;

    public string $checkoutType = 'standard'; 
    public bool $orderSubmitted = false;
    public ?int $trackedOrderId = null;
    public ?string $mpesaErrorMessage = null;
    public ?int $activePaymentId = null;
    public string $paymentStatus = 'idle'; 
    public ?string $mpesaReceiptNumber = null;
    public string $paymentMethod = 'mpesa'; // mpesa or net_30
    
    // Catalog Pagination
    public int $perPage = 12;

    // Reviews properties
    public ?int $quickViewProductId = null;
    public array $newReview = [
        'rating' => 5,
        'quality_rating' => 5,
        'freshness_rating' => 5,
        'value_rating' => 5,
        'comment' => ''
    ];
    
    // Support Chat logs
    public bool $chatOpen = false;
    public string $chatMessage = '';
    public array $chatHistory = [
        ['sender' => 'bot', 'text' => 'Welcome to Aura — your luxury curation companion. Ask me about popular arrangements, locations, my latest order status, or loyalty rewards!']
    ];
    public bool $autoOpenDrawer = false;

    public function loadMore(): void
    {
        $this->perPage += 12;
    }

    public function getAddressSuggestions(): array
    {
        $registry = [
            'Nairobi' => [
                "Riverside Drive, Office Park Complexes, Nairobi",
                "Westlands, Delta Corner / PwC Towers, Westlands, Nairobi",
                "Gigiri, UN Avenue / Diplomatic Enclave, Nairobi",
                "Kilimani, Lenana Road Business Hubs, Kilimani, Nairobi",
                "Karen, Miotoni Road Luxury Residences, Karen, Nairobi",
                "Runda Estate, Pan African Insurance Quadrant, Runda, Nairobi",
                "Muthaiga, Old Muthaiga Road Estates, Muthaiga, Nairobi",
                "Thika Road, Garden City Business Quadrant, Nairobi",
                "Lavington Mall & Restaurants, Lavington, Nairobi",
                "Yaya Centre Shopping Complex, Kilimani, Nairobi",
                "Sarit Centre Mall, Karuna Road, Westlands, Nairobi",
                "Alchemist Bar & Dining, Parklands, Nairobi",
                "CJ's Restaurant, Koinange Street, CBD, Nairobi",
                "Mercado Mexican Kitchen, Kenrail Towers, Westlands, Nairobi",
                "Kitisuru Terrace Apartments, Kitisuru, Nairobi",
                "Valley Arcade Shopping Centre, Lavington, Nairobi",
                "Jomo Kenyatta International Airport (JKIA), Embakasi, Nairobi",
                "Nairobi National Park Main Gate, Langata Road, Nairobi"
            ],
            'Kiambu' => [
                "Limuru, Tea Estate Curation Ridge, Limuru, Kiambu",
                "Ruaka, Two Rivers Office Tower Matrix, Ruaka, Kiambu",
                "Two Rivers Mall & Theme Park, Ruaka, Kiambu",
                "Village Market, Limuru Road, Gigiri Border, Kiambu",
                "Runda Evergreen Apartments, Kiambu Road, Kiambu",
                "Ciata City Shopping Mall, Kiambu Road, Kiambu",
                "Thika Road Mall (TRM), Roysambu Border, Kiambu",
                "Sigona Golf Club Residences, Kikuyu, Kiambu",
                "Tatu City Industrial & Residential Zone, Ruiru, Kiambu",
                "Kasarani-Mwiki Road Apartments, Kiambu Border, Kiambu",
                "Kiambu Golf Club & Lounge, Kiambu Town, Kiambu",
                "Zuri Cascades Residences, Tatton Road, Kiambu",
                "Jomo Kenyatta University (JKUAT) Gate 1, Juja, Kiambu",
                "Craving Yellow Restaurant, Kiambu Town, Kiambu"
            ]
        ];

        return $registry[$this->region] ?? [];
    }

    public function updatedRegion($value): void
    {
        $this->delivery_address = '';
        $this->deliveryCity = $value;
        $this->validateDeliveryDetails();
    }

    public function mount(): void
    {
        $this->deliveryCity = session()->get('nb_delivery_city', '');
        $this->deliveryDate = session()->get('nb_delivery_date', '');
        $this->deliverySlot = session()->get('nb_delivery_slot', 'standard');
        $this->validateDeliveryDetails();

        $rawCart = session()->get('noir_bloom_cart', []);
        $this->cart = [];
        foreach ($rawCart as $key => $qty) {
            if (is_numeric($key)) {
                $this->cart[$key . '-standard'] = $qty;
            } else {
                $this->cart[$key] = $qty;
            }
        }
        $this->updatedDeliveryType();

        // Load authenticated user profile data if available
        if (auth()->check()) {
            $user = auth()->user();
            $this->full_name = $user->name;
            $this->email = $user->email;
            $this->phone = $this->cleanLocalPhone($user->phone_number ?? '');
            $this->kra_pin = $user->kra_pin ?? '';
            $this->delivery_address = $user->default_delivery_address ?? '';
            $this->region = $user->default_region ?? 'Nairobi';
            $this->checkoutType = $user->account_tier?->value === 'corporate' ? 'corporate' : 'standard';

            if (empty($this->deliveryCity)) {
                $this->deliveryCity = $this->region;
            }
        }

        if (session()->get('open_curation_drawer_after_login')) {
            $this->autoOpenDrawer = true;
            session()->forget('open_curation_drawer_after_login');
        }
    }

    public function validateDeliveryDetails(): void
    {
        if (!empty($this->deliveryCity) && !empty($this->deliveryDate)) {
            $this->deliveryDetailsValid = true;
            session()->put('nb_delivery_city', $this->deliveryCity);
            session()->put('nb_delivery_date', $this->deliveryDate);
            session()->put('nb_delivery_slot', $this->deliverySlot);
        } else {
            $this->deliveryDetailsValid = false;
        }
    }

    public function updatedDeliveryCity(): void
    {
        $this->validateDeliveryDetails();
    }

    public function updatedDeliveryDate(): void
    {
        $this->validateDeliveryDetails();
    }

    public function updatedDeliverySlot(): void
    {
        $this->validateDeliveryDetails();
    }

    public function updatedDeliveryType(): void
    {
        $this->service_fee = match($this->delivery_type) {
            'secret' => 500,
            'concierge' => 1500,
            default => 0,
        };
    }


    public function selectCategory(string $category): void
    {
        $this->selectedCategory = $category;
        $this->perPage = 6;
    }

    public function updatedSearch(): void
    {
        $this->perPage = 6;
    }

    public function updatedSortBy(): void
    {
        $this->perPage = 6;
    }


    public function addToCuration(int $productId, string $size = 'standard'): void
    {
        $this->orderSubmitted = false;
        $this->mpesaErrorMessage = null;

        $product = Product::find($productId);
        if (!$product) return;

        // If product has custom sizes
        if ($product->sizes && is_array($product->sizes)) {
            $sizeInfo = null;
            foreach ($product->sizes as $s) {
                if (strtolower($s['name']) === strtolower($size)) {
                    $sizeInfo = $s;
                    break;
                }
            }
            if ($sizeInfo) {
                $currentQty = 0;
                $key = $productId . '-' . $size;
                if (isset($this->cart[$key])) {
                    $currentQty = $this->cart[$key];
                }
                if ($currentQty + 1 > $sizeInfo['stock']) {
                    session()->flash('error', "Cannot add '{$size}' size. Out of stock (only {$sizeInfo['stock']} items available).");
                    return;
                }
            }
        } else {
            // Size stock consumption multipliers: Standard = 1, Deluxe = 2, Grand = 3
            $sizeMultiplier = match($size) {
                'deluxe' => 2,
                'grand' => 3,
                default => 1,
            };

            // Calculate baseline units currently in cart for this product
            $consumedUnits = 0;
            foreach ($this->cart as $cartKey => $qty) {
                $parts = explode('-', $cartKey);
                if ((int)$parts[0] === $productId) {
                    $itemSize = $parts[1] ?? 'standard';
                    $multiplier = match($itemSize) {
                        'deluxe' => 2,
                        'grand' => 3,
                        default => 1,
                    };
                    $consumedUnits += $qty * $multiplier;
                }
            }

            // Validate physical stock constraints
            if ($consumedUnits + $sizeMultiplier > $product->stock) {
                session()->flash('error', "Cannot add '{$size}' size. Out of stock (requires {$sizeMultiplier} units, only " . ($product->stock - $consumedUnits) . " units left).");
                return;
            }
        }

        $key = $productId . '-' . $size;
        if (isset($this->cart[$key])) {
            $this->cart[$key]++;
        } else {
            $this->cart[$key] = 1;
        }

        session()->put('noir_bloom_cart', $this->cart);
    }

    public function removeFromCuration(int $productId, string $size = 'standard'): void
    {
        $key = $productId . '-' . $size;
        if (isset($this->cart[$key])) {
            $this->cart[$key]--;
            if ($this->cart[$key] <= 0) {
                unset($this->cart[$key]);
            }
        }

        session()->put('noir_bloom_cart', $this->cart);
    }

    public function sendChatMessage(): void
    {
        if (empty(trim($this->chatMessage))) return;

        $ipKey = 'aura-chat:' . request()->ip();
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($ipKey, 15)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($ipKey);
            $this->chatHistory[] = [
                'sender' => 'bot', 
                'text' => "I am receiving too many requests from your location. Please pause for {$seconds} seconds."
            ];
            $this->chatMessage = '';
            return;
        }
        \Illuminate\Support\Facades\RateLimiter::hit($ipKey, 60);

        $userQuery = $this->chatMessage;
        $this->chatHistory[] = ['sender' => 'user', 'text' => $userQuery];
        $this->chatMessage = '';

        $lowerQuery = strtolower($userQuery);
        $reply = "I'm Aura, your luxury curation companion. I am currently consulting the database to assist you.";

        if (str_contains($lowerQuery, 'secret admirer') || str_contains($lowerQuery, 'anonymous') || str_contains($lowerQuery, 'uniformed') || str_contains($lowerQuery, 'concierge delivery')) {
            $reply = "For ultimate premium delivery, we support:\n• Secret Admirer Delivery: We deliver the luxury bouquet completely anonymously, keeping your identity secret from the recipient.\n• Uniformed Concierge Drop-off: A white-gloved professional in tailored attire delivers the bouquet, perfect for corporate events and formal occasions.";
        } elseif (str_contains($lowerQuery, 'birthday') || str_contains($lowerQuery, 'anniversary') || str_contains($lowerQuery, 'sympathy') || str_contains($lowerQuery, 'graduation') || str_contains($lowerQuery, 'vase') || str_contains($lowerQuery, 'occasion')) {
            $reply = "Our curation occasions are tailored to specific design philosophies:\n• Birthday Celebration: Uses festive, bright, and vibrant flower palettes.\n• Anniversary & Love: Centered around romantic deep reds, spray roses, and soft pastels.\n• Graduation & Success: Bright, celebratory color blends for achievement.\n• Sympathy & Comfort: Serene whites, calm lilies, and soothing tones.\n• Vase Bundle: Designed specifically for home/office structural defaults.";
        } elseif (str_contains($lowerQuery, 'net 30') || str_contains($lowerQuery, 'b2b') || str_contains($lowerQuery, 'invoice') || str_contains($lowerQuery, 'credit limit') || str_contains($lowerQuery, 'corporate checkout')) {
            $reply = "For registered corporate B2B clients, we offer net 30 payment options subject to credit limit approval. When checking out, choose the 'Corporate' tab, enter your KRA PIN, and select Net 30. A tax-compliant eTIMS invoice will be generated automatically upon approval.";
        } elseif (str_contains($lowerQuery, 'source') || str_contains($lowerQuery, 'naivasha') || str_contains($lowerQuery, 'limuru') || str_contains($lowerQuery, 'farm') || str_contains($lowerQuery, 'export') || str_contains($lowerQuery, 'grower')) {
            $reply = "We source our Grade A export-quality stems directly from premier growers in Naivasha and Limuru. Naivasha's volcanic soil yields large, vibrant rose heads, while Limuru's cool highlands produce elegant white lilies and lush green foliage.";
        } elseif (str_contains($lowerQuery, 'delivery') || str_contains($lowerQuery, 'route') || str_contains($lowerQuery, 'ship')) {
            $reply = "We offer standard delivery via our Nairobi and Kiambu hubs. You can also upgrade to a Secret Admirer delivery (keeping the sender anonymous) or an elegant Uniformed Concierge drop-off at checkout.";
        } elseif (str_contains($lowerQuery, 'curat') || str_contains($lowerQuery, 'studio') || str_contains($lowerQuery, 'builder') || str_contains($lowerQuery, 'custom') || str_contains($lowerQuery, 'desk') || str_contains($lowerQuery, 'bouquet') || str_contains($lowerQuery, 'make') || str_contains($lowerQuery, 'design')) {
            $reply = "Our Curation Studio is a bespoke luxury design desk. You can customize every aspect:\n1. Occasion (00 Occasion) to set the design philosophy (e.g. Birthday, Anniversary, Sympathy).\n2. Blooms (01 Blooms) to select fresh export-grade stems.\n3. Wrapping & Accents (02 Wrapping) to select premium packaging, satin ribbons, and glitter dust.\n4. Fragrance Mist (03 Scent) to mist the petals with custom scent.\n5. Treats (04 Treats) to pair with fine wine, jewelry, or chocolate.\n6. Handwritten Letter (05 Note) for calligraphy personalization.\nClick 'Curate Selection' in the top menu to start your creation!";
        } elseif (str_contains($lowerQuery, 'theme') || str_contains($lowerQuery, 'color') || str_contains($lowerQuery, 'style') || str_contains($lowerQuery, 'onyx') || str_contains($lowerQuery, 'champagne') || str_contains($lowerQuery, 'rose')) {
            $reply = "Atelier Noir & Bloom features two beautiful visual themes tailored to your aesthetic:\n• Dark: A dark, gold-accented high-fashion mode.\n• Light: A clean, minimal, warm cream design system.\nYou can instantly change themes via the selector buttons in the top navigation header.";
        } elseif (str_contains($lowerQuery, 'service') || str_contains($lowerQuery, 'subscription') || str_contains($lowerQuery, 'event') || str_contains($lowerQuery, 'wedding') || str_contains($lowerQuery, 'corp')) {
            $reply = "Beyond retail arrangements, we offer premium bespoke services:\n• Corporate Subscriptions: Weekly fresh rotations for hotels, offices, and showrooms.\n• Event Curation: Visual design, setups, and floral structures for weddings, private dinners, and galas.\n• Custom Hampers: Tailored luxury gifting suites and treat baskets.\nContact our events concierge at concierge@noirbloom.co.ke for details.";
        } elseif (str_contains($lowerQuery, 'contact') || str_contains($lowerQuery, 'phone') || str_contains($lowerQuery, 'email') || str_contains($lowerQuery, 'support') || str_contains($lowerQuery, 'concierge') || str_contains($lowerQuery, 'call') || str_contains($lowerQuery, 'number')) {
            $reply = "Our concierge team is at your disposal:\n• Hotline Direct: +254 (0) 712354697\n• Email Dispatch: concierge@noirbloom.co.ke\nOperating Hours:\n• Mon - Sat: 07:00 - 20:00\n• Sunday: 09:00 - 17:00";
        } elseif (str_contains($lowerQuery, 'tims') || str_contains($lowerQuery, 'vat') || str_contains($lowerQuery, 'tax')) {
            $reply = "Noir & Bloom is fully eTIMS compliant. Simply choose 'Corporate eTIMS' during checkout to input your KRA PIN and automatically generate a tax-compliant invoice.";
        } elseif (str_contains($lowerQuery, 'grade') || str_contains($lowerQuery, 'wholesale')) {
            $reply = "Our wholesale line features premium export Grade A stem bundles sourced directly from Naivasha and Limuru growers. Minimum order quantities apply.";
        } elseif (str_contains($lowerQuery, 'product') || str_contains($lowerQuery, 'flower') || str_contains($lowerQuery, 'rose') || str_contains($lowerQuery, 'catalog')) {
            $products = Product::where('stock', '>', 0)->limit(3)->get();
            if ($products->count() > 0) {
                $names = $products->map(fn($p) => "• {$p->name} ({$p->formatted_price})")->join("\n");
                $reply = "Here are some of our popular available arrangements:\n" . $names . "\nYou can click 'Curate Selection' to add them to your basket.";
            } else {
                $reply = "We offer a curated selection of retail arrangements, wholesale stems, and custom luxury hampers. Explore our showroom tags below!";
            }
        } elseif (str_contains($lowerQuery, 'branch') || str_contains($lowerQuery, 'location') || str_contains($lowerQuery, 'where')) {
            $branches = Branch::where('is_active', true)->get();
            if ($branches->count() > 0) {
                $locs = $branches->map(fn($b) => "• {$b->name} in {$b->location_city}")->join("\n");
                $reply = "Noir & Bloom operates from the following physical design ateliers:\n" . $locs;
            } else {
                $reply = "We deliver across Nairobi and Kiambu regions, fulfilled by our local design hubs.";
            }
        } elseif (str_contains($lowerQuery, 'price') || str_contains($lowerQuery, 'cheapest') || str_contains($lowerQuery, 'cost')) {
            $cheapest = Product::orderBy('price', 'asc')->first();
            $expensive = Product::orderBy('price', 'desc')->first();
            if ($cheapest && $expensive) {
                $reply = "Our pricing starts at {$cheapest->formatted_price} for the '{$cheapest->name}' up to {$expensive->formatted_price} for our premium '{$expensive->name}'.";
            }
        } elseif (str_contains($lowerQuery, 'loyalty') || str_contains($lowerQuery, 'point') || str_contains($lowerQuery, 'tier') || str_contains($lowerQuery, 'silver') || str_contains($lowerQuery, 'gold') || str_contains($lowerQuery, 'bronze')) {
            if (auth()->check()) {
                $user = auth()->user();
                $reply = "Hello " . $user->name . "! You currently have " . $user->loyalty_points . " loyalty points (Tier: " . ($user->account_tier->value ?? 'Standard') . "). You earn 1 point for every 100 KSH spent!\nOur loyalty program features four exclusive tiers:\n• Standard: Entry level for all registered members.\n• Bronze: Unlocks free premium mists on all curations.\n• Silver: Adds complimentary handwritten calligraphy greeting cards.\n• Gold: Grants free hand-curation service fees and early concierge access.";
            } else {
                $reply = "Our Loyalty Program rewards you with 1 point for every 100 KSH spent. We feature four exclusive tiers:\n• Standard: Entry level for all registered members.\n• Bronze: Unlocks free premium mists on all curations.\n• Silver: Adds complimentary handwritten calligraphy greeting cards.\n• Gold: Grants free hand-curation service fees and early concierge access.\nSign up or log in at checkout to start earning points towards exclusive tiers!";
            }
        } elseif (str_contains($lowerQuery, 'status') || str_contains($lowerQuery, 'track') || str_contains($lowerQuery, 'order')) {
            if (auth()->check()) {
                $client = Client::where('email', auth()->user()->email)->first();
                $latestOrder = $client ? $client->orders()->latest()->first() : null;
                if ($latestOrder) {
                    $reply = "Your latest order is #NB-ORD-" . str_pad($latestOrder->id, 4, '0', STR_PAD_LEFT) . ". Status: " . strtoupper($latestOrder->status) . " (placed on " . $latestOrder->created_at->format('d M Y') . ").";
                } else {
                    $reply = "You don't have any orders yet. Place an order to track its status!";
                }
            } else {
                $reply = "To track your order, please log in to your account, or refer to the email updates sent by our logistics team.";
            }
        } else {
            $reply = "I'm Aura, your luxury curation assistant. Ask me about custom designs/curations, visual themes, our events/corporate services, locations, latest order status, or loyalty rewards!";
        }

        $this->chatHistory[] = ['sender' => 'bot', 'text' => $reply];
    }

    public function submitCurationRequest(): void
    {
        $ipKey = 'checkout-attempt:' . request()->ip();
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($ipKey, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($ipKey);
            $this->addError('paymentMethod', "Too many checkout requests from your IP. Please wait {$seconds} seconds.");
            return;
        }
        \Illuminate\Support\Facades\RateLimiter::hit($ipKey, 60);

        $this->validate([
            'full_name'        => 'required|string|min:3',
            'email'            => 'required|email',
            'phone'            => 'required|string',
            'delivery_address' => 'required|string|min:6',
            'region'           => 'required|in:Nairobi,Kiambu',
            'deliveryCity'     => 'required|string',
            'deliveryDate'     => 'required|date|after_or_equal:today',
            'recipient_name'   => $this->is_gift ? 'required|string|min:3' : 'nullable',
            'recipient_phone'  => $this->is_gift ? 'required|string|min:9' : 'nullable',
            'kra_pin'          => $this->checkoutType === 'corporate' ? 'required|string|min:11' : 'nullable',
        ]);

        if (empty($this->cart)) {
            return;
        }

        // Calculate grand total for credit validation
        $productIds = [];
        foreach (array_keys($this->cart) as $key) {
            $parts = explode('-', $key);
            $productIds[] = $parts[0];
        }
        $products = Product::findMany(array_unique($productIds));
        
        $totalAmount = 0;
        $itemsToAttach = [];

        // Final inventory reservation check
        foreach ($this->cart as $key => $quantity) {
            $parts = explode('-', $key);
            $productId = $parts[0];
            $size = $parts[1] ?? 'standard';
            $product = $products->firstWhere('id', $productId);
            if ($product) {
                if ($product->sizes && is_array($product->sizes)) {
                    $sizeInfo = null;
                    foreach ($product->sizes as $s) {
                        if (strtolower($s['name']) === strtolower($size)) {
                            $sizeInfo = $s;
                            break;
                        }
                    }
                    if ($sizeInfo) {
                        if ($quantity > $sizeInfo['stock']) {
                            $this->addError('paymentMethod', "Order contains items that exceed physical stock parameters. '{$product->name}' ({$size}) has only {$sizeInfo['stock']} units available, but your selection requires {$quantity} units.");
                            return;
                        }
                    }
                } else {
                    $requiredUnits = 0;
                    foreach ($this->cart as $k => $q) {
                        $p = explode('-', $k);
                        if ($p[0] === $productId) {
                            $s = $p[1] ?? 'standard';
                            $mult = match($s) {
                                'deluxe' => 2,
                                'grand' => 3,
                                default => 1,
                            };
                            $requiredUnits += $q * $mult;
                        }
                    }
                    
                    if ($requiredUnits > $product->stock) {
                        $this->addError('paymentMethod', "Order contains items that exceed physical stock parameters. '{$product->name}' has only {$product->stock} stock units available, but your selection requires {$requiredUnits} units.");
                        return;
                    }
                }
            }
        }

        foreach ($this->cart as $key => $quantity) {
            $parts = explode('-', $key);
            $productId = $parts[0];
            $size = $parts[1] ?? 'standard';
            $product = $products->firstWhere('id', $productId);
            if ($product) {
                $finalPrice = null;
                $costPriceAtSale = 0;
                
                if ($product->sizes && is_array($product->sizes)) {
                    foreach ($product->sizes as $s) {
                        if (strtolower($s['name']) === strtolower($size)) {
                            $finalPrice = (int)$s['price'];
                            $costPriceAtSale = (int)($s['cost_price'] ?? 0);
                            break;
                        }
                    }
                }
                
                if ($finalPrice === null) {
                    $priceMultiplier = match($size) {
                        'deluxe' => 1.5,
                        'grand' => 2.2,
                        default => 1.0
                    };
                    $finalPrice = (int) round($product->price * $priceMultiplier);
                    $costPriceAtSale = (int) round($product->cost_price * $priceMultiplier);
                }
                
                $totalAmount += ($finalPrice * $quantity);
                
                $itemsToAttach[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price_at_sale' => $finalPrice,
                    'cost_price_at_sale' => $costPriceAtSale,
                    'size' => $size,
                ];
            }
        }
        $grandTotal = $totalAmount + $this->service_fee;

        // B2B credit validation before proceeding
        if ($this->checkoutType === 'corporate' && $this->paymentMethod === 'net_30') {
            $existingClient = Client::where('email', trim(strtolower($this->email)))->first();
            
            if (!$existingClient) {
                $this->addError('paymentMethod', 'Corporate credit account not found. Please register or use M-Pesa.');
                return;
            }

            if ($existingClient->payment_terms !== 'net_30') {
                $this->addError('paymentMethod', 'Your corporate profile is not authorized for credit terms (prepaid only).');
                return;
            }

            if ($existingClient->outstanding_balance + $grandTotal > $existingClient->credit_limit) {
                $this->addError('paymentMethod', "Credit limit exceeded. Outstanding: " . number_format($existingClient->outstanding_balance) . " KSH, Order: " . number_format($grandTotal) . " KSH, Limit: " . number_format($existingClient->credit_limit) . " KSH.");
                return;
            }
        }

        $orderId = DB::transaction(function () use ($itemsToAttach, $grandTotal) {
            $client = Client::updateOrCreate(
                ['email' => trim(strtolower($this->email))],
                [
                    'user_id'          => auth()->check() ? auth()->id() : null,
                    'company_name'     => $this->checkoutType === 'corporate' ? $this->company_name : null,
                    'kra_pin'          => $this->checkoutType === 'corporate' ? strtoupper(trim($this->kra_pin)) : null,
                    'contact_name'     => $this->full_name,
                    'phone'            => $this->phone,
                    'region'           => $this->region,
                    'delivery_address' => $this->delivery_address,
                ]
            );

            $targetBranch = Branch::where('location_city', $this->region)->where('is_active', true)->first();

            $customizations = session()->get('noir_bloom_customizations', []);
            $customMsg = $customizations['card_message'] ?? null;
            $printPref = $customizations['card_print_preference'] ?? 'handwritten';
            $ribbonColor = $customizations['ribbon_color'] ?? null;
            $glitter = $customizations['glitter'] ?? 'No';
            $curationOccasion = $customizations['curation_occasion'] ?? null;

            $specialInstructions = 'Delivery Package: ' . strtoupper($this->delivery_type);
            if ($curationOccasion) {
                $specialInstructions .= ' | Curation Occasion: ' . strtoupper($curationOccasion);
            }
            if ($customMsg) {
                $noteLabel = ($printPref === 'typography' || $printPref === 'printed') ? 'Printed Note' : 'Handwritten Note';
                $specialInstructions .= ' | ' . $noteLabel . ': ' . $customMsg;
            }
            if ($ribbonColor && $ribbonColor !== 'None') {
                $specialInstructions .= ' | Ribbon Accent: ' . $ribbonColor;
            }
            if ($glitter === 'Yes') {
                $specialInstructions .= ' | Glitter Accent: Yes';
            }

            $order = Order::create([
                'client_id'            => $client->id,
                'branch_id'            => $targetBranch?->id ?? null,
                'is_gift'              => $this->is_gift,
                'recipient_name'       => $this->is_gift ? $this->recipient_name : null,
                'recipient_phone'      => $this->is_gift ? $this->recipient_phone : null,
                'total_amount'         => $grandTotal,
                'service_fee_amount'   => $this->service_fee,
                'status'               => 'pending',
                'special_instructions' => $specialInstructions,
            ]);

            foreach ($itemsToAttach as $item) {
                $order->products()->attach($item['product_id'], [
                    'quantity' => $item['quantity'],
                    'price_at_sale' => $item['price_at_sale'],
                    'cost_price_at_sale' => $item['cost_price_at_sale'],
                    'size' => $item['size'],
                ]);
            }

            // B2B Net 30 Ledger Update
            if ($this->checkoutType === 'corporate' && $this->paymentMethod === 'net_30') {
                $client->increment('outstanding_balance', $grandTotal);

                \App\Models\AccountsReceivableInvoice::create([
                    'order_id' => $order->id,
                    'client_id' => $client->id,
                    'amount_due' => $grandTotal,
                    'due_at' => now()->addDays(30),
                    'status' => 'unpaid',
                ]);
            }

            return $order->id;
        });

        $this->trackedOrderId = $orderId;
        $this->orderSubmitted = true;

        if ($this->checkoutType === 'corporate' && $this->paymentMethod === 'net_30') {
            // Approve corporate credit order immediately
            $order = Order::find($orderId);
            app(\App\Services\OrderService::class)->approve($order);

            $this->paymentStatus = 'completed';
            $this->mpesaReceiptNumber = null;
            $this->cart = [];
            session()->forget('noir_bloom_cart');
            session()->forget('noir_bloom_customizations');
        } elseif ($this->paymentMethod === 'mpesa') {
            $this->paymentStatus = 'idle';
        }
    }

    /**
     * Initiate an M-Pesa STK Push for the tracked order.
     */
    public function initiateMpesaPayment(MpesaService $mpesa): void
    {
        $this->mpesaErrorMessage = null;

        $this->phone = $this->cleanLocalPhone($this->phone);

        $this->validate([
            'phone' => 'required|string|regex:/^(7|1)[0-9]{8}$/',
        ], [
            'phone.regex' => 'Please enter a valid 9-digit Kenyan phone number (e.g. 712345678).',
        ]);

        $ipKey = 'stk-push-ip:' . request()->ip();
        $phoneKey = 'stk-push-phone:' . $this->phone;
        
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($ipKey, 3) || \Illuminate\Support\Facades\RateLimiter::tooManyAttempts($phoneKey, 3)) {
            $seconds = max(
                \Illuminate\Support\Facades\RateLimiter::availableIn($ipKey),
                \Illuminate\Support\Facades\RateLimiter::availableIn($phoneKey)
            );
            $this->mpesaErrorMessage = "Too many STK push requests. Please wait {$seconds} seconds before retrying.";
            $this->paymentStatus = 'idle';
            return;
        }
        
        \Illuminate\Support\Facades\RateLimiter::hit($ipKey, 60);
        \Illuminate\Support\Facades\RateLimiter::hit($phoneKey, 60);

        if (!$this->trackedOrderId) {
            $this->mpesaErrorMessage = 'No active order found for payment processing.';
            return;
        }

        $order = Order::find($this->trackedOrderId);
        if (!$order) {
            $this->mpesaErrorMessage = 'Order reference could not be resolved.';
            return;
        }

        try {
            // Create a pending payment tracking record first
            $payment = Payment::create([
                'order_id' => $order->id,
                'phone_number' => $this->phone,
                'amount' => $order->total_amount,
                'status' => 'pending',
            ]);

            $this->activePaymentId = $payment->id;
            $this->paymentStatus = 'pending';

            // Dispatch background queue job with afterCommit constraint to prevent race conditions
            \App\Jobs\SendMpesaStkPushJob::dispatch(
                $payment->id,
                $this->phone,
                $order->total_amount,
                $order->id
            )->afterCommit();

        } catch (\Exception $e) {
            $this->paymentStatus = 'failed';
            $this->mpesaErrorMessage = 'Payment initiation failed: ' . $e->getMessage();
        }
    }

    public function checkPaymentStatus(): void
    {
        if (!$this->activePaymentId) return;

        $payment = Payment::find($this->activePaymentId);
        if (!$payment) return;

        if ($payment->status === 'completed') {
            $this->paymentStatus = 'completed';
            $this->mpesaReceiptNumber = $payment->mpesa_receipt_number;
            $this->cart = [];
            session()->forget('noir_bloom_cart');
            session()->forget('noir_bloom_customizations');
        } elseif ($payment->status === 'failed') {
            $this->paymentStatus = 'failed';
            $this->mpesaErrorMessage = $payment->result_description ?: 'STK Push rejected or expired.';
        } else {
            // Check if 60 seconds have elapsed since payment creation to prevent infinite loop
            if ($payment->created_at && $payment->created_at->diffInSeconds(now()) > 60) {
                $payment->update([
                    'status' => 'failed',
                    'result_description' => 'Payment transaction timed out (60s).'
                ]);
                $this->paymentStatus = 'failed';
                $this->mpesaErrorMessage = 'STK authorization prompt expired. Please try again.';
            } else {
                $this->paymentStatus = 'pending';
            }
        }
    }

    public function returnToCollections(): void
    {
        $this->cart = [];
        session()->forget('noir_bloom_cart');
        session()->forget('noir_bloom_customizations');
        $this->reset([
            'is_gift', 'recipient_name', 'recipient_phone', 'delivery_type', 'service_fee', 
            'orderSubmitted', 'trackedOrderId', 'mpesaErrorMessage',
            'activePaymentId', 'paymentStatus', 'mpesaReceiptNumber'
        ]);
    }

    public function cancelPayment(): void
    {
        if ($this->trackedOrderId) {
            $order = Order::find($this->trackedOrderId);
            if ($order) {
                $order->update(['status' => 'cancelled']);
            }
        }

        $this->reset([
            'orderSubmitted',
            'paymentStatus',
            'activePaymentId',
            'trackedOrderId',
            'mpesaErrorMessage',
            'mpesaReceiptNumber'
        ]);
    }

    public function render()
    {
        $this->slides = \App\Services\HeroSettingsService::getSlides();

        $occasions = \Illuminate\Support\Facades\Cache::remember('occasions_all', 3600, fn() => Occasion::all());
        
        $query = Product::with('occasions')
            ->whereNotIn('category', ['specializtion', 'specialization', 'specializations']);

        if ($this->selectedCategory !== 'all') {
            $cats = match (strtolower($this->selectedCategory)) {
                'bouquet', 'bouquets' => ['bouquet', 'bouquets'],
                'giftings', 'gifting', 'hampers' => ['giftings', 'gifting', 'hampers'],
                'specializtion', 'specialization', 'specializations' => ['specializtion', 'specialization', 'specializations'],
                default => [$this->selectedCategory],
            };
            $query->whereIn('category', $cats);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }



        if ($this->sortBy === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($this->sortBy === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($this->sortBy === 'popularity') {
            $query->withCount('orders')->orderBy('orders_count', 'desc');
        } else {
            $query->latest();
        }

        $limit = ($this->selectedCategory === 'all' && empty($this->search))
            ? max(18, $this->perPage)
            : $this->perPage;

        $products = $query->take($limit)->get()->map(function($product) {
            if ($product->category === 'specializtion' || $product->category === 'specialization' || $product->category === 'specializations') {
                $product->backdrop_url = $product->image_url ?: 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&q=80&w=600';
            } else {
                $product->backdrop_url = $product->image_url ?: match($product->category) {
                    'stems'   => 'https://images.unsplash.com/photo-1596436889106-be35e843f974?auto=format&fit=crop&q=80&w=600',
                    'giftings', 'hampers' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&q=80&w=600',
                    default   => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&q=80&w=600',
                };
            }
            $product->stock_standard = $product->stock;
            $product->stock_deluxe = (int) floor($product->stock * 0.7);
            $product->stock_grand = (int) floor($product->stock * 0.4);
            return $product;
        });

        $totalCountQuery = Product::query()
            ->whereNotIn('category', ['specializtion', 'specialization', 'specializations']);
        if ($this->selectedCategory !== 'all') {
            $cats = match (strtolower($this->selectedCategory)) {
                'bouquet', 'bouquets' => ['bouquet', 'bouquets'],
                'giftings', 'gifting', 'hampers' => ['giftings', 'gifting', 'hampers'],
                'specializtion', 'specialization', 'specializations' => ['specializtion', 'specialization', 'specializations'],
                default => [$this->selectedCategory],
            };
            $totalCountQuery->whereIn('category', $cats);
        }
        if (!empty($this->search)) {
            $totalCountQuery->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        $totalCount = $totalCountQuery->count();

        $userOrders = collect();
        if (auth()->check()) {
            $client = Client::where('email', auth()->user()->email)->first();
            if ($client) {
                $userOrders = $client->orders()->with(['products', 'payments'])->latest()->limit(5)->get();
            }
        }

        $cartItems = $this->compileCartItems();
        $cartTotal = array_sum(array_column($cartItems, 'subtotal'));

        $suggestionQuery = Product::where('stock', '>', 0);
        if (!empty(trim($this->search))) {
            $suggestionQuery->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }
        $suggestions = $suggestionQuery->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.storefront', [
            'occasions'       => $occasions,
            'products'        => $products,
            'suggestions'     => $suggestions,
            'cartItems'       => $cartItems,
            'cartTotal'       => $cartTotal,
            'cartCount'       => array_sum($this->cart),
            'activeColor'     => '#E5E5E5',
            'userOrders'      => $userOrders,
            'hasMore'         => $products->count() < $totalCount,
        ])->layout('components.layouts.app');
    }

    protected function compileCartItems(): array
    {
        if (empty($this->cart)) {
            return [];
        }

        $productIds = [];
        foreach (array_keys($this->cart) as $key) {
            $parts = explode('-', $key);
            $productIds[] = $parts[0];
        }

        $products = Product::whereIn('id', array_unique($productIds))->get()->keyBy('id');
        $items = [];

        foreach ($this->cart as $key => $quantity) {
            $parts = explode('-', $key);
            $id = $parts[0];
            $size = $parts[1] ?? 'standard';
            $product = $products->get($id);
            if ($product) {
                $finalPrice = null;
                if ($product->sizes && is_array($product->sizes)) {
                    foreach ($product->sizes as $s) {
                        if (strtolower($s['name']) === strtolower($size)) {
                            $finalPrice = (int)$s['price'];
                            break;
                        }
                    }
                }

                if ($finalPrice === null) {
                    $priceMultiplier = match($size) {
                        'deluxe' => 1.5,
                        'grand' => 2.2,
                        default => 1.0
                    };
                    $finalPrice = (int) round($product->price * $priceMultiplier);
                }

                $clonedProduct = clone $product;
                $clonedProduct->price = $finalPrice;
                if ($size !== 'standard') {
                    $clonedProduct->name = $product->name . ' (' . ucfirst($size) . ')';
                }

                $items[] = [
                    'product'     => $clonedProduct,
                    'quantity'    => $quantity,
                    'subtotal'    => $finalPrice * $quantity,
                    'size'        => $size,
                    'original_id' => $id,
                ];
            }
        }

        return $items;
    }

    public function prepareGuestCheckoutRedirect(string $target): void
    {
        session()->put('open_curation_drawer_after_login', true);
        $this->redirect($target, navigate: true);
    }

    public function toggleWishlist(int $productId): void
    {
        if (!auth()->check()) {
            session()->flash('error', 'Please log in to manage your wishlist.');
            return;
        }

        $user = auth()->user();
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
        session()->flash('success', $msg);
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
            'quality_rating' => 5,
            'freshness_rating' => 5,
            'value_rating' => 5,
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
            'newReview.quality_rating' => 'required|integer|min:1|max:5',
            'newReview.freshness_rating' => 'required|integer|min:1|max:5',
            'newReview.value_rating' => 'required|integer|min:1|max:5',
            'newReview.comment' => 'required|string|min:3|max:1000',
        ]);

        \App\Models\Review::create([
            'product_id' => $this->quickViewProductId,
            'user_id' => $user->id,
            'rating' => $this->newReview['rating'],
            'quality_rating' => $this->newReview['quality_rating'],
            'freshness_rating' => $this->newReview['freshness_rating'],
            'value_rating' => $this->newReview['value_rating'],
            'comment' => $this->newReview['comment'],
        ]);

        $this->resetReviewForm();
        session()->flash('success_review', 'Review submitted successfully!');
    }

    public function clearCuration(): void
    {
        $this->cart = [];
        session()->forget('noir_bloom_cart');
    }

    protected function cleanLocalPhone(string $phone): string
    {
        $cleaned = preg_replace('/\D/', '', $phone);
        
        // Strip country code if present
        if (str_starts_with($cleaned, '254')) {
            $cleaned = substr($cleaned, 3);
        }
        
        // Strip leading 0 if present (e.g. 0734... -> 734...)
        if (str_starts_with($cleaned, '0')) {
            $cleaned = substr($cleaned, 1);
        }
        
        return $cleaned;
    }
}