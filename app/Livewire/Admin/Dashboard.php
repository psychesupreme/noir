<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use App\Models\Payment;
use App\Models\WastageLog;
use App\Models\PurchaseOrder;
use App\Models\BranchProductStock;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = \Illuminate\Support\Facades\Cache::remember('dashboard_stats', 300, function () {
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

            // Month COGS & Margin calculations
            $monthCogs = DB::table('order_product')
                ->join('orders', 'order_product.order_id', '=', 'orders.id')
                ->join('products', 'order_product.product_id', '=', 'products.id')
                ->where('orders.status', '!=', 'cancelled')
                ->where('orders.created_at', '>=', now()->startOfMonth())
                ->sum(DB::raw('order_product.quantity * products.cost_price'));

            $monthMargin = $monthRevenue - $monthCogs;
            $monthMarginPercent = $monthRevenue > 0 ? round(($monthMargin / $monthRevenue) * 100, 1) : 0;

            // Monthly Wastage
            $monthWastage = WastageLog::where('created_at', '>=', now()->startOfMonth())->sum('cost_estimate');

            // Supply Chain Statistics
            $openPosCount = PurchaseOrder::whereIn('status', ['draft', 'ordered', 'partial'])->count();
            $pendingDeliveriesCount = PurchaseOrder::where('status', 'ordered')->count();

            // Low branch stock alerts
            $lowBranchStocks = BranchProductStock::whereColumn('stock', '<=', 'min_stock_level')
                ->with(['branch', 'product'])
                ->orderBy('stock')
                ->limit(10)
                ->get();

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

            return [
                'todayRevenue' => $todayRevenue,
                'weekRevenue' => $weekRevenue,
                'monthRevenue' => $monthRevenue,
                'allTimeRevenue' => $allTimeRevenue,
                'monthCogs' => $monthCogs,
                'monthMargin' => $monthMargin,
                'monthMarginPercent' => $monthMarginPercent,
                'monthWastage' => $monthWastage,
                'openPosCount' => $openPosCount,
                'pendingDeliveriesCount' => $pendingDeliveriesCount,
                'lowBranchStocks' => $lowBranchStocks,
                'ordersByStatus' => $ordersByStatus,
                'topProducts' => $topProducts,
                'recentOrders' => $recentOrders,
                'completedPayments' => $completedPayments,
                'pendingPayments' => $pendingPayments,
                'failedPayments' => $failedPayments,
                'totalClients' => $totalClients,
                'totalProducts' => $totalProducts,
                'totalOrders' => $totalOrders,
            ];
        });

        return view('livewire.admin.dashboard', $stats)->layout('components.layouts.admin', ['title' => 'Noir & Bloom | ERP Dashboard']);
    }
}

