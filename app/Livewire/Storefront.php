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

    public array $cart = [];

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
    
    // Support Chat logs
    public bool $chatOpen = false;
    public string $chatMessage = '';
    public array $chatHistory = [
        ['sender' => 'bot', 'text' => 'Welcome to the Noir & Bloom Concierge. How may I assist you with your flower curation or corporate delivery specification today?']
    ];

    public array $addressSuggestions = [
        "Riverside Drive, Office Park Complexes, Nairobi",
        "Westlands, Delta Corner / PwC Towers, Nairobi",
        "Gigiri, UN Avenue / Diplomatic Enclave, Nairobi",
        "Kilimani, Lenana Road Business Hubs, Nairobi",
        "Karen, Miotoni Road Luxury Residences, Nairobi",
        "Runda Estate, Pan African Insurance Quadrant, Nairobi",
        "Muthaiga, Old Muthaiga Road Estates, Nairobi",
        "Limuru, Tea Estate Curation Ridge, Kiambu",
        "Thika Road, Garden City Business Quadrant, Nairobi",
        "Ruaka, Two Rivers Office Tower Matrix, Kiambu"
    ];

    public function mount(): void
    {
        $this->cart = session()->get('noir_bloom_cart', []);
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
    }

    public function selectCategory(string $category): void
    {
        $this->selectedCategory = $category;
    }

    public function addToCuration(int $productId): void
    {
        $this->orderSubmitted = false;
        $this->mpesaErrorMessage = null;

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]++;
        } else {
            $this->cart[$productId] = 1;
        }

        session()->put('noir_bloom_cart', $this->cart);
    }

    public function removeFromCuration(int $productId): void
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]--;
            if ($this->cart[$productId] <= 0) {
                unset($this->cart[$productId]);
            }
        }

        session()->put('noir_bloom_cart', $this->cart);
    }

    public function sendChatMessage(): void
    {
        if (empty(trim($this->chatMessage))) return;

        $userQuery = $this->chatMessage;
        $this->chatHistory[] = ['sender' => 'user', 'text' => $userQuery];
        $this->chatMessage = '';

        $reply = "Our concierge agents have received your message and will follow up shortly via phone line.";
        $lowerQuery = strtolower($userQuery);

        if (str_contains($lowerQuery, 'delivery') || str_contains($lowerQuery, 'route')) {
            $reply = "We offer standard delivery via our Nairobi and Kiambu hubs. You can also upgrade to a Secret Admirer delivery or an elegant Uniformed Concierge drop-off at checkout.";
        } elseif (str_contains($lowerQuery, 'tims') || str_contains($lowerQuery, 'vat') || str_contains($lowerQuery, 'tax')) {
            $reply = "Noir & Bloom is fully eTIMS compliant. Simply choose 'Corporate eTIMS' during checkout to input your KRA PIN and automatically generate a tax invoice.";
        } elseif (str_contains($lowerQuery, 'grade') || str_contains($lowerQuery, 'wholesale')) {
            $reply = "Our wholesale line features premium export Grade A stem bundles sourced directly from Naivasha and Limuru growers.";
        }

        $this->chatHistory[] = ['sender' => 'bot', 'text' => $reply];
    }

    public function submitCurationRequest(): void
    {
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

        $orderId = DB::transaction(function () {
            $client = Client::updateOrCreate(
                ['email' => trim(strtolower($this->email))],
                [
                    'company_name'     => $this->checkoutType === 'corporate' ? $this->company_name : null,
                    'kra_pin'          => $this->checkoutType === 'corporate' ? strtoupper(trim($this->kra_pin)) : null,
                    'contact_name'     => $this->full_name,
                    'phone'            => $this->phone,
                    'region'           => $this->region,
                    'delivery_address' => $this->delivery_address,
                ]
            );

            $targetBranch = Branch::where('location_city', $this->region)->where('is_active', true)->first();
            $products = Product::findMany(array_keys($this->cart));
            $totalAmount = 0;
            $pivotPayload = [];

            foreach ($this->cart as $productId => $quantity) {
                $product = $products->firstWhere('id', $productId);
                if ($product) {
                    $totalAmount += ($product->price * $quantity);
                    $pivotPayload[$productId] = [
                        'quantity'      => $quantity,
                        'price_at_sale' => $product->price,
                    ];
                }
            }

            $grandTotal = $totalAmount + $this->service_fee;

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

            $order->products()->sync($pivotPayload);

            return $order->id;
        });

        $this->trackedOrderId = $orderId;
        $this->orderSubmitted = true;
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
            $response = $mpesa->sendStkPush(
                phone: $this->phone,
                amount: $order->total_amount,
                orderId: $order->id
            );

            // Create a pending payment tracking record
            Payment::create([
                'order_id' => $order->id,
                'phone_number' => $this->phone,
                'amount' => $order->total_amount,
                'status' => 'pending',
                'merchant_request_id' => $response['MerchantRequestID'] ?? null,
                'checkout_request_id' => $response['CheckoutRequestID'] ?? null,
            ]);

        } catch (\Exception $e) {
            $this->mpesaErrorMessage = 'Payment initiation failed: ' . $e->getMessage();
        }
    }

    public function returnToCollections(): void
    {
        $this->cart = [];
        session()->forget('noir_bloom_cart');
        $this->reset(['is_gift', 'recipient_name', 'recipient_phone', 'delivery_type', 'service_fee', 'orderSubmitted', 'trackedOrderId', 'mpesaErrorMessage']);
    }

    public function render()
    {
        $occasions = Occasion::all();
        $productsQuery = Product::with('occasions')->latest();

        if ($this->selectedCategory !== 'all') {
            $productsQuery->where('category', $this->selectedCategory);
        }

        if (!empty($this->search)) {
            $productsQuery->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedOccasion) {
            $productsQuery->whereHas('occasions', function ($query) {
                $query->where('slug', $this->selectedOccasion);
            });
        }

        // Map premium open-source stock image arrays based on category lines
        $showroomProducts = $productsQuery->get()->map(function($product) {
            $product->backdrop_url = $product->image_url ?: match($product->category) {
                'wholesale' => 'https://images.unsplash.com/photo-1596436889106-be35e843f974?auto=format&fit=crop&q=80&w=600', // Graded bundles
                'gifting'   => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&q=80&w=600', // Premium hampers
                default     => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&q=80&w=600', // Architectural retail
            };
            return $product;
        });

        $userOrders = collect();
        if (auth()->check()) {
            $client = Client::where('email', auth()->user()->email)->first();
            if ($client) {
                $userOrders = $client->orders()->with(['products', 'payments'])->latest()->limit(5)->get();
            }
        }

        return view('livewire.storefront', [
            'occasions'    => $occasions,
            'products'     => $showroomProducts,
            'cartItems'    => $this->compileCartItems(),
            'cartTotal'    => $this->getCartTotal(),
            'cartCount'    => array_sum($this->cart),
            'activeColor'  => $this->selectedOccasion ? $occasions->firstWhere('slug', $this->selectedOccasion)?->accent_color : '#E5E5E5',
            'userOrders'   => $userOrders,
        ])->layout('components.layouts.app');
    }


    protected function compileCartItems(): array
    {
        $items = [];
        foreach ($this->cart as $id => $quantity) {
            $product = Product::find($id);
            if ($product) {
                $items[] = [
                    'product'  => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ];
            }
        }
        return $items;
    }

    protected function getCartTotal(): int
    {
        $total = 0;
        foreach ($this->cart as $id => $quantity) {
            $product = Product::find($id);
            if ($product) {
                $total += ($product->price * $quantity);
            }
        }
        return $total;
    }
}