<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Client;
use App\Models\Branch;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReportIndex extends Component
{
    public string $startDate = '';
    public string $endDate = '';

    public function mount(): void
    {
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function render()
    {
        $start = $this->startDate . ' 00:00:00';
        $end = $this->endDate . ' 23:59:59';

        // 1. KPI Aggregates
        $completedOrdersQuery = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$start, $end]);

        $totalSales = $completedOrdersQuery->sum('total_amount');
        $orderCount = $completedOrdersQuery->count();
        $avgTicket = $orderCount > 0 ? (int) ($totalSales / $orderCount) : 0;
        
        $activeClients = Client::whereHas('orders', function ($q) use ($start, $end) {
            $q->where('status', '!=', 'cancelled')->whereBetween('created_at', [$start, $end]);
        })->count();

        // 2. Daily Sales Trend (for animated SVG line chart)
        $dailySalesTrend = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 3. Category Distribution (for SVG Donut chart)
        $categoryBreakdown = Product::select('products.category', DB::raw('SUM(order_product.quantity * order_product.price_at_sale) as volume'))
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('products.category')
            ->get()
            ->pluck('volume', 'category')
            ->toArray();

        // Ensure category keys exist
        $categoryBreakdown = array_merge([
            'retail' => 0,
            'wholesale' => 0,
            'gifting' => 0
        ], $categoryBreakdown);

        // 4. Branch Fulfillment Volumes (for CSS animated Bar chart)
        $hubPerformance = Branch::select('branches.name', 'branches.code')
            ->selectRaw('COALESCE(SUM(orders.total_amount), 0) as revenue')
            ->leftJoin('orders', function ($join) use ($start, $end) {
                $join->on('branches.id', '=', 'orders.branch_id')
                     ->where('orders.status', '!=', 'cancelled')
                     ->whereBetween('orders.created_at', [$start, $end]);
            })
            ->groupBy('branches.id', 'branches.name', 'branches.code')
            ->get();

        return view('livewire.admin.report-index', [
            'totalSales' => $totalSales,
            'orderCount' => $orderCount,
            'avgTicket' => $avgTicket,
            'activeClients' => $activeClients,
            'dailySalesTrend' => $dailySalesTrend,
            'categoryBreakdown' => $categoryBreakdown,
            'hubPerformance' => $hubPerformance,
        ])->layout('components.layouts.admin');
    }
}
