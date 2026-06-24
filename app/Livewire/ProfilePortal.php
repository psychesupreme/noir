<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class ProfilePortal extends Component
{
    use \App\Livewire\Traits\HasNotificationsAndTheme;
    // Tabs: details, security, orders, wishlist, settings, logistics
    public string $activeTab = 'details';

    // Profile Details Form
    public string $name = '';
    public string $email = '';
    public string $phone_number = '';
    public string $gender = '';
    public string $dob = '';
    public string $kra_pin = '';
    public string $default_delivery_address = '';
    public string $default_region = 'Nairobi';

    // Password Update
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    // Settings / Preferences
    public bool $notification_email = true;
    public bool $notification_sms = false;
    public bool $notification_concierge = false;
    public bool $notification_newsletter = false;
    public string $preferred_theme = 'dark';

    // Logistics status update success feedback (for staff tab)
    public ?int $updatedOrderId = null;

    // Order feedback & rating states
    public array $orderRatings = [];
    public array $orderFeedbacks = [];

    // Detailed rating modal states
    public ?int $ratingOrderId = null;
    public int $ratingOverall = 0;
    public int $ratingProduct = 0;
    public int $ratingPackaging = 0;
    public int $ratingDelivery = 0;
    public string $ratingComments = '';
    public array $ratingProducts = [];
    public array $productReviews = [];

    protected $rules = [
        'name' => 'required|string|min:3',
        'email' => 'required|email',
        'phone_number' => 'nullable|string',
        'gender' => 'nullable|string|in:male,female,trans,other',
        'dob' => 'nullable|date',
        'kra_pin' => 'nullable|string|min:11|max:15',
        'default_delivery_address' => 'nullable|string|min:6',
        'default_region' => 'required|in:Nairobi,Kiambu',
    ];

    public function mount(): void
    {
        $user = auth()->user();
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone_number = $user->phone_number ?? '';
            $this->gender = $user->gender ?? '';
            $this->dob = $user->dob ?? '';
            $this->kra_pin = $user->kra_pin ?? '';
            $this->default_delivery_address = $user->default_delivery_address ?? '';
            $this->default_region = $user->default_region ?? 'Nairobi';

            // Load settings
            $settings = $user->settings ?? [];
            $this->notification_email = $settings['notification_email'] ?? true;
            $this->notification_sms = $settings['notification_sms'] ?? false;
            $this->notification_concierge = $settings['notification_concierge'] ?? false;
            $this->notification_newsletter = $settings['notification_newsletter'] ?? false;
            
            $theme = $settings['preferred_theme'] ?? 'dark';
            $this->preferred_theme = ($theme === 'onyx' || $theme === 'dark') ? 'dark' : 'light';
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function updateProfile(): void
    {
        $this->validate();

        $user = auth()->user();
        if ($user) {
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'gender' => $this->gender ?: null,
                'dob' => $this->dob ?: null,
                'kra_pin' => strtoupper($this->kra_pin),
                'default_delivery_address' => $this->default_delivery_address,
                'default_region' => $this->default_region,
            ]);

            // Sync with CRM Client table
            Client::updateOrCreate(
                ['email' => $user->email],
                [
                    'user_id' => $user->id,
                    'contact_name' => $user->name,
                    'phone' => $user->phone_number,
                    'gender' => $user->gender,
                    'dob' => $user->dob,
                    'kra_pin' => $user->kra_pin,
                    'delivery_address' => $user->default_delivery_address,
                    'region' => $user->default_region,
                ]
            );

            session()->flash('success_profile', 'Atelier profile details updated successfully.');
        }
    }

    public function updateSettings(): void
    {
        $user = auth()->user();
        if ($user) {
            $settings = $user->settings ?? [];
            $settings['notification_email'] = $this->notification_email;
            $settings['notification_sms'] = $this->notification_sms;
            $settings['notification_concierge'] = $this->notification_concierge;
            $settings['notification_newsletter'] = $this->notification_newsletter;
            $settings['preferred_theme'] = $this->preferred_theme;

            $user->update([
                'settings' => $settings
            ]);

            session()->flash('success_settings', 'Atelier preferences saved successfully.');
            $this->dispatch('theme-settings-changed', $this->preferred_theme);
        }
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();
        if ($user && Hash::check($this->current_password, $user->password)) {
            $user->update([
                'password' => Hash::make($this->new_password),
            ]);
            $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
            session()->flash('success_password', 'Password updated successfully.');
        } else {
            session()->flash('error_password', 'Current password credentials do not match.');
        }
    }

    public function removeFromWishlist(int $productId): void
    {
        $user = auth()->user();
        if ($user) {
            $settings = $user->settings ?? [];
            $wishlist = $settings['wishlist'] ?? [];
            
            // Remove matching item
            $wishlist = array_values(array_filter($wishlist, fn($id) => $id != $productId));
            $settings['wishlist'] = $wishlist;

            $user->update([
                'settings' => $settings
            ]);

            session()->flash('success_wishlist', 'Item removed from your wishlist.');
        }
    }

    public function addToCurationFromWishlist(int $productId): void
    {
        $product = Product::find($productId);
        if (!$product) return;

        $cart = session()->get('noir_bloom_cart', []);
        $cart[$productId . '-standard'] = ($cart[$productId . '-standard'] ?? 0) + 1;
        session()->put('noir_bloom_cart', $cart);

        session()->flash('success_wishlist', 'Added "' . $product->name . '" to your curation cart.');
    }

    public function updateLogisticsStatus(int $orderId, string $status): void
    {
        $user = auth()->user();
        if (!$user || !$user->account_tier->isStaff()) {
            abort(403, 'Unauthorized: Only internal staff can manage logistics runs.');
        }

        $order = Order::find($orderId);
        if ($order) {
            $order->update(['status' => $status]);
            $this->updatedOrderId = $orderId;
            session()->flash('success_logistics', 'Delivery run nb-ord-' . $orderId . ' status set to ' . strtoupper($status));
        }
    }

    public function submitRating(int $orderId): void
    {
        $rating = $this->orderRatings[$orderId] ?? null;
        $feedback = $this->orderFeedbacks[$orderId] ?? null;

        if ($rating) {
            $order = Order::find($orderId);
            if ($order) {
                $user = auth()->user();
                if ($user && $order->client && $order->client->email === $user->email) {
                    $order->update([
                        'rating' => $rating,
                        'feedback' => $feedback,
                    ]);
                    session()->flash('success_orders', 'Thank you for your feedback on order #NB-ORD-' . $orderId . '!');
                }
            }
        }
    }

    public function openRatingModal(int $orderId): void
    {
        $this->ratingOrderId = $orderId;
        $this->ratingOverall = 0;
        $this->ratingProduct = 0;
        $this->ratingPackaging = 0;
        $this->ratingDelivery = 0;
        $this->ratingComments = '';

        $order = Order::with('products')->find($orderId);
        if ($order) {
            $this->ratingProducts = $order->products->toArray();
            $this->productReviews = [];
            foreach ($order->products as $p) {
                $this->productReviews[$p->id] = [
                    'rating' => 5,
                    'quality_rating' => 5,
                    'freshness_rating' => 5,
                    'value_rating' => 5,
                    'comment' => '',
                ];
            }
        } else {
            $this->ratingProducts = [];
            $this->productReviews = [];
        }
    }

    public function submitDetailedRating(): void
    {
        $this->validate([
            'ratingOverall' => 'required|integer|min:1|max:5',
            'ratingProduct' => 'required|integer|min:1|max:5',
            'ratingPackaging' => 'required|integer|min:1|max:5',
            'ratingDelivery' => 'required|integer|min:1|max:5',
            'ratingComments' => 'nullable|string',
            'productReviews.*.rating' => 'required|integer|min:1|max:5',
            'productReviews.*.quality_rating' => 'required|integer|min:1|max:5',
            'productReviews.*.freshness_rating' => 'required|integer|min:1|max:5',
            'productReviews.*.value_rating' => 'required|integer|min:1|max:5',
            'productReviews.*.comment' => 'nullable|string',
        ]);

        $order = Order::find($this->ratingOrderId);
        if ($order) {
            $user = auth()->user();
            if ($user && $order->client && $order->client->email === $user->email) {
                $order->update([
                    'rating' => $this->ratingOverall,
                    'feedback' => $this->ratingComments,
                    'product_rating' => $this->ratingProduct,
                    'packaging_rating' => $this->ratingPackaging,
                    'delivery_rating' => $this->ratingDelivery,
                ]);

                // Save product-level reviews
                foreach ($this->productReviews as $productId => $revData) {
                    \App\Models\Review::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'product_id' => $productId,
                        ],
                        [
                            'rating' => $revData['rating'],
                            'quality_rating' => $revData['quality_rating'],
                            'freshness_rating' => $revData['freshness_rating'],
                            'value_rating' => $revData['value_rating'],
                            'comment' => !empty(trim($revData['comment'] ?? '')) ? trim($revData['comment']) : null,
                        ]
                    );
                }

                session()->flash('success_orders', 'Thank you for your feedback on order #NB-ORD-' . $this->ratingOrderId . '!');
                $this->ratingOrderId = null;
            }
        }
    }

    public function render()
    {
        $user = auth()->user();
        $userOrders = collect();
        $assignedRuns = collect();
        $wishlistProducts = collect();

        if ($user) {
            // Load Client order history
            $client = Client::where('email', $user->email)->first();
            if ($client) {
                $userOrders = $client->orders()->with(['products', 'payments'])->latest()->get();
            }

            // Load Wishlist products
            $settings = $user->settings ?? [];
            $wishlistIds = $settings['wishlist'] ?? [];
            if (!empty($wishlistIds)) {
                $wishlistProducts = Product::whereIn('id', $wishlistIds)->get();
            }

            // Load Logistics runs (All orders pending/processing/delivered for drivers/riders dashboard)
            if ($user->account_tier->isStaff()) {
                $assignedRuns = Order::with(['client', 'products'])->latest()->limit(15)->get();
            }
        }

        // Count total items currently stored in the session-based shopping cart
        $cartCount = array_sum(session()->get('noir_bloom_cart', []));

        return view('livewire.profile-portal', [
            'userOrders' => $userOrders,
            'assignedRuns' => $assignedRuns,
            'wishlistProducts' => $wishlistProducts,
            'user' => $user,
            'cartCount' => $cartCount,
        ])->layout('components.layouts.app');
    }
}
