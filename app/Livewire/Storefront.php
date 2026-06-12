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
    #[Url(as: 'collection', history: true)]
    public ?string $selectedOccasion = null;

    #[Url(as: 'find', history: true)]
    public string $search = '';
    
    #[Url(as: 'tier', history: true)]
    public string $selectedCategory = 'all'; 

    #[Url(as: 'sort', history: true)]
    public string $sortBy = 'latest';


    public array $cart = [];
    public array $slides = [];

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
    public int $perPage = 6;
    
    // Support Chat logs
    public bool $chatOpen = false;
    public string $chatMessage = '';
    public array $chatHistory = [
        ['sender' => 'bot', 'text' => 'Welcome to Aura — your luxury curation companion. Ask me about popular arrangements, locations, my latest order status, or loyalty rewards!']
    ];
    public bool $autoOpenDrawer = false;

    public function loadMore(): void
    {
        $this->perPage += 4;
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
    }

    public function mount(): void
    {
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
            $this->phone = $user->phone_number ?? '';
            $this->kra_pin = $user->kra_pin ?? '';
            $this->delivery_address = $user->default_delivery_address ?? '';
            $this->region = $user->default_region ?? 'Nairobi';
            $this->checkoutType = $user->account_tier?->value === 'corporate' ? 'corporate' : 'standard';
        }

        if (session()->get('open_curation_drawer_after_login')) {
            $this->autoOpenDrawer = true;
            session()->forget('open_curation_drawer_after_login');
        }
    }

    public function updatedDeliveryType(): void
    {
        $this->service_fee = match($this->delivery_type) {
            'secret' => 500,
            'concierge' => 1500,
            default => 0,
        };
    }

    public function filterByOccasion(?string $slug = null): void
    {
        $this->selectedOccasion = $slug;
        $this->perPage = 6;
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

        $stock = match($size) {
            'deluxe' => (int) floor($product->stock * 0.7),
            'grand' => (int) floor($product->stock * 0.4),
            default => $product->stock,
        };

        $key = $productId . '-' . $size;
        $currentQty = $this->cart[$key] ?? 0;

        if ($currentQty >= $stock) {
            session()->flash('error', 'Cannot add more of this size due to stock limits.');
            return;
        }

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

        if (str_contains($lowerQuery, 'delivery') || str_contains($lowerQuery, 'route')) {
            $reply = "We offer standard delivery via our Nairobi and Kiambu hubs. You can also upgrade to a Secret Admirer delivery or an elegant Uniformed Concierge drop-off at checkout.";
        } elseif (str_contains($lowerQuery, 'tims') || str_contains($lowerQuery, 'vat') || str_contains($lowerQuery, 'tax')) {
            $reply = "Noir & Bloom is fully eTIMS compliant. Simply choose 'Corporate eTIMS' during checkout to input your KRA PIN and automatically generate a tax invoice.";
        } elseif (str_contains($lowerQuery, 'grade') || str_contains($lowerQuery, 'wholesale')) {
            $reply = "Our wholesale line features premium export Grade A stem bundles sourced directly from Naivasha and Limuru growers.";
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
        } elseif (str_contains($lowerQuery, 'loyalty') || str_contains($lowerQuery, 'points') || str_contains($lowerQuery, 'tier')) {
            if (auth()->check()) {
                $user = auth()->user();
                $reply = "Hello " . $user->name . "! You currently have " . $user->loyalty_points . " loyalty points (Tier: " . ($user->account_tier->value ?? 'Standard') . "). You earn 1 point for every 100 KSH spent!";
            } else {
                $reply = "Our Loyalty Program rewards you with 1 point for every 100 KSH spent. Sign up or log in at checkout to start earning points towards exclusive tiers!";
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
            $reply = "I'm Aura, your luxury curation assistant. Ask me about popular arrangements, locations, my latest order status, or loyalty rewards!";
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

        foreach ($this->cart as $key => $quantity) {
            $parts = explode('-', $key);
            $productId = $parts[0];
            $size = $parts[1] ?? 'standard';
            $product = $products->firstWhere('id', $productId);
            if ($product) {
                $priceMultiplier = match($size) {
                    'deluxe' => 1.5,
                    'grand' => 2.2,
                    default => 1.0
                };
                $finalPrice = (int) round($product->price * $priceMultiplier);
                $totalAmount += ($finalPrice * $quantity);
                
                $itemsToAttach[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price_at_sale' => $finalPrice
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

            $order = Order::create([
                'client_id'            => $client->id,
                'branch_id'            => $targetBranch?->id ?? null,
                'is_gift'              => $this->is_gift,
                'recipient_name'       => $this->is_gift ? $this->recipient_name : null,
                'recipient_phone'      => $this->is_gift ? $this->recipient_phone : null,
                'total_amount'         => $grandTotal,
                'service_fee_amount'   => $this->service_fee,
                'status'               => 'pending',
                'special_instructions' => 'Delivery Package: ' . strtoupper($this->delivery_type),
            ]);

            foreach ($itemsToAttach as $item) {
                $order->products()->attach($item['product_id'], [
                    'quantity' => $item['quantity'],
                    'price_at_sale' => $item['price_at_sale']
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
        }
    }

    /**
     * Initiate an M-Pesa STK Push for the tracked order.
     */
    public function initiateMpesaPayment(MpesaService $mpesa): void
    {
        $this->mpesaErrorMessage = null;

        $this->validate([
            'phone' => 'required|string|min:9',
        ]);

        $ipKey = 'stk-push-ip:' . request()->ip();
        $phoneKey = 'stk-push-phone:' . preg_replace('/\D/', '', $this->phone);
        
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
        $this->reset([
            'is_gift', 'recipient_name', 'recipient_phone', 'delivery_type', 'service_fee', 
            'orderSubmitted', 'trackedOrderId', 'mpesaErrorMessage',
            'activePaymentId', 'paymentStatus', 'mpesaReceiptNumber'
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

        if ($this->selectedOccasion) {
            $query->whereHas('occasions', function ($q) {
                $q->where('slug', $this->selectedOccasion);
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

        $products = $query->take($this->perPage)->get()->map(function($product) {
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
        if ($this->selectedOccasion) {
            $totalCountQuery->whereHas('occasions', function ($q) {
                $q->where('slug', $this->selectedOccasion);
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
            'activeColor'     => $this->selectedOccasion ? $occasions->firstWhere('slug', $this->selectedOccasion)?->accent_color : '#E5E5E5',
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
                $priceMultiplier = match($size) {
                    'deluxe' => 1.5,
                    'grand' => 2.2,
                    default => 1.0
                };
                $finalPrice = (int) round($product->price * $priceMultiplier);

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
}