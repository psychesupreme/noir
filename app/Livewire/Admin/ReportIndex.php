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

    /**
     * Stream P&L operational metrics as a CSV file.
     */
    public function exportPL()
    {
        $start = $this->startDate . ' 00:00:00';
        $end = $this->endDate . ' 23:59:59';

        $revenue = Order::where('status', '!=', 'cancelled')->whereBetween('created_at', [$start, $end])->sum('total_amount');
        
        $cogs = (int) DB::table('order_product')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$start, $end])
            ->sum(DB::raw('order_product.quantity * products.cost_price'));

        $wastage = (int) \App\Models\WastageLog::whereBetween('created_at', [$start, $end])->sum('cost_estimate');
        $netProfit = $revenue - $cogs - $wastage;

        $filename = 'corporate_pl_statement_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->streamDownload(function() use ($start, $end, $revenue, $cogs, $wastage, $netProfit) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Noir & Bloom - B2B Operational Profit & Loss Statement']);
            fputcsv($file, ['Reporting Period', $start . ' to ' . $end]);
            fputcsv($file, []);
            fputcsv($file, ['Financial Ledger Metric', 'Amount (KES)']);
            fputcsv($file, ['Total Gross Sales Revenue', number_format($revenue, 2, '.', '')]);
            fputcsv($file, ['Cost of Goods Sold (COGS)', number_format($cogs, 2, '.', '')]);
            fputcsv($file, ['Inventory Wastage Costs', number_format($wastage, 2, '.', '')]);
            fputcsv($file, ['Net Operating Profit', number_format($netProfit, 2, '.', '')]);
            
            fclose($file);
        }, $filename, $headers);
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

        // 5. P&L Financial Calculations
        $cogs = (int) DB::table('order_product')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$start, $end])
            ->sum(DB::raw('order_product.quantity * products.cost_price'));

        $wastage = (int) \App\Models\WastageLog::whereBetween('created_at', [$start, $end])->sum('cost_estimate');
        $netProfit = $totalSales - $cogs - $wastage;

        return view('livewire.admin.report-index', [
            'totalSales' => $totalSales,
            'orderCount' => $orderCount,
            'avgTicket' => $avgTicket,
            'activeClients' => $activeClients,
            'dailySalesTrend' => $dailySalesTrend,
            'categoryBreakdown' => $categoryBreakdown,
            'hubPerformance' => $hubPerformance,
            'cogs' => $cogs,
            'wastage' => $wastage,
            'netProfit' => $netProfit,
        ])->layout('components.layouts.admin');
    }
}
