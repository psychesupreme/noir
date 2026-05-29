<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\LoyaltyTransaction;
use App\Services\EtimsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiCheckoutController extends Controller
{
    /**
     * Process checkout request from mobile client.
     */
    public function checkout(Request $request, EtimsService $etims)
    {
        $request->validate([
            'checkout_type' => 'required|in:standard,corporate',
            'full_name' => 'required|string|min:3',
            'email' => 'required|email',
            'phone' => 'required|string',
            'delivery_address' => 'required|string|min:6',
            'region' => 'required|in:Nairobi,Kiambu',
            'is_gift' => 'nullable|boolean',
            'recipient_name' => 'required_if:is_gift,true|nullable|string|min:3',
            'recipient_phone' => 'required_if:is_gift,true|nullable|string|min:9',
            'company_name' => 'required_if:checkout_type,corporate|nullable|string|min:3',
            'kra_pin' => 'required_if:checkout_type,corporate|nullable|string|min:11',
            'special_instructions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // 1. Inventory validation check
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                return response()->json([
                    'message' => "Product ID {$item['product_id']} not found."
                ], 422);
            }
            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'message' => 'Insufficient stock for product: ' . $product->name,
                    'errors' => [
                        'items' => ["The product '{$product->name}' has insufficient stock. Available: {$product->stock}, Requested: {$item['quantity']}."]
                    ]
                ], 422);
            }
        }

        // 2. Process Order via DB Transaction
        $order = DB::transaction(function () use ($request, $etims) {
            // Find or update client by email
            $client = Client::updateOrCreate(
                ['email' => trim(strtolower($request->email))],
                [
                    'company_name'     => $request->checkout_type === 'corporate' ? $request->company_name : null,
                    'kra_pin'          => $request->checkout_type === 'corporate' ? strtoupper(trim($request->kra_pin)) : null,
                    'contact_name'     => $request->full_name,
                    'phone'            => $request->phone,
                    'region'           => $request->region,
                    'delivery_address' => $request->delivery_address,
                ]
            );

            // Locate target branch based on region city match
            $targetBranch = Branch::where('location_city', $request->region)
                ->where('is_active', true)
                ->first();

            // Calculate pricing
            $products = Product::findMany(collect($request->items)->pluck('product_id'));
            $subtotal = 0;
            $pivotPayload = [];

            foreach ($request->items as $item) {
                $product = $products->firstWhere('id', $item['product_id']);
                if ($product) {
                    $subtotal += ($product->price * $item['quantity']);
                    $pivotPayload[$product->id] = [
                        'quantity' => $item['quantity'],
                        'price_at_sale' => $product->price,
                    ];
                }
            }

            // Mobile checkout delivery service fee constant
            $serviceFee = 500;
            $grandTotal = $subtotal + $serviceFee;

            // Create Order - Auto approved for instant validation & fulfillment simulation
            $order = Order::create([
                'client_id' => $client->id,
                'branch_id' => $targetBranch?->id ?? null,
                'is_gift' => (bool) $request->is_gift,
                'recipient_name' => $request->is_gift ? $request->recipient_name : null,
                'recipient_phone' => $request->is_gift ? $request->recipient_phone : null,
                'total_amount' => $grandTotal,
                'service_fee_amount' => $serviceFee,
                'status' => 'approved', // Auto approved for instant checkouts
                'special_instructions' => $request->special_instructions ?? 'API Mobile Order',
            ]);

            // Associate items
            $order->products()->sync($pivotPayload);

            // 3. Inventory Deduction
            foreach ($order->products as $product) {
                $product->decrement('stock', $product->pivot->quantity);
            }

            // 4. Award Loyalty Points
            $user = User::where('email', $client->email)->first();
            if ($user) {
                $pointsEarned = (int) ($order->total_amount / 100);
                if ($pointsEarned > 0) {
                    $user->increment('loyalty_points', $pointsEarned);
                    LoyaltyTransaction::create([
                        'user_id' => $user->id,
                        'points' => $pointsEarned,
                        'type' => 'earn',
                        'description' => "Points earned on order #NB-ORD-" . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                    ]);
                }
            }

            // 5. KRA eTIMS Transmission
            $invoice = $etims->initializeFiscalInvoice($order);
            $etims->transmitToKra($invoice);

            return $order;
        });

        // Reload relationships to return clean payload
        $order->load(['client', 'products', 'etimsInvoice', 'branch']);

        return response()->json([
            'message' => 'Order placed successfully and fiscal invoice transmitted.',
            'order' => $order
        ], 201);
    }
}
