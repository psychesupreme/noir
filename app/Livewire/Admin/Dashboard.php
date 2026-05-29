<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // Revenue metrics
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $weekRevenue = Order::where('created_at', '>=', now()->startOfWeek())
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $monthRevenue = Order::where('created_at', '>=', now()->startOfMonth())
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $allTimeRevenue = Order::where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // Order pipeline counts
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Top selling products (by quantity sold)
        $topProducts = Product::select('products.name', 'products.sku', 'products.price', 'products.stock')
            ->selectRaw('COALESCE(SUM(order_product.quantity), 0) as total_sold')
            ->leftJoin('order_product', 'products.id', '=', 'order_product.product_id')
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.price', 'products.stock')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Low stock alerts (stock <= 10)
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->orderBy('stock')
            ->get();

        // Recent orders
        $recentOrders = Order::with(['client', 'products'])
            ->latest()
            ->limit(8)
            ->get();

        // Payment stats
        $completedPayments = Payment::where('status', 'completed')->count();
        $pendingPayments = Payment::where('status', 'pending')->count();
        $failedPayments = Payment::where('status', 'failed')->count();

        // Client stats
        $totalClients = Client::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();

        return view('livewire.admin.dashboard', [
            'todayRevenue' => $todayRevenue,
            'weekRevenue' => $weekRevenue,
            'monthRevenue' => $monthRevenue,
            'allTimeRevenue' => $allTimeRevenue,
            'ordersByStatus' => $ordersByStatus,
            'topProducts' => $topProducts,
            'lowStockProducts' => $lowStockProducts,
            'recentOrders' => $recentOrders,
            'completedPayments' => $completedPayments,
            'pendingPayments' => $pendingPayments,
            'failedPayments' => $failedPayments,
            'totalClients' => $totalClients,
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
        ])->layout('components.layouts.admin');
    }
}
