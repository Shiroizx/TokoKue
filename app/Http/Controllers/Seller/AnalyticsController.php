<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display sales analytics page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Check if the user has a shop
        $shop = Shop::where('user_id', $user->id)->first();
        
        
        // If the user doesn't have a shop yet, redirect to shop creation
        if (!$shop) {
            return redirect()->route('seller.shop.create')
                ->with('warning', 'Please create your shop to continue as a seller.');
        }

        // Get filter parameters
        $period = $request->input('period', 'monthly'); // Default to monthly
        $dateRange = $request->input('dateRange', 'last6months'); // Default to last 6 months
        $startDate = null;
        $endDate = null;
        
        // Set date range based on selection
        switch ($dateRange) {
            case 'last7days':
                $startDate = Carbon::now()->subDays(7)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'last30days':
                $startDate = Carbon::now()->subDays(30)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'last3months':
                $startDate = Carbon::now()->subMonths(3)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'last6months':
                $startDate = Carbon::now()->subMonths(6)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'lastyear':
                $startDate = Carbon::now()->subYear()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'custom':
                $startDate = $request->input('startDate') ? Carbon::parse($request->input('startDate'))->startOfDay() : Carbon::now()->subMonths(6)->startOfDay();
                $endDate = $request->input('endDate') ? Carbon::parse($request->input('endDate'))->endOfDay() : Carbon::now()->endOfDay();
                break;
        }

        // Find all seller's product IDs
        $productIds = Product::where('seller_id', $user->id)
            ->pluck('id')
            ->toArray();

        // Get order IDs associated with seller's products
        $orderIds = DB::table('order_items')
            ->whereIn('product_id', $productIds)
            ->distinct()
            ->pluck('order_id')
            ->toArray();

        // Initialize data containers
        $salesOverviewData = [];
        $salesOverviewLabels = [];
        $orderCountData = [];
        $revenueData = [
            'totalRevenue' => 0,
            'averageOrderValue' => 0,
            'previousPeriodComparison' => 0
        ];
        $topSellingProducts = [];
        $salesByCategory = [];
        $customerLocationData = [];
        

        // Calculate Revenue Data (Products only, excluding shipping)
        // Get current period product revenue
        $currentProductRevenue = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('order_items.product_id', $productIds)
            ->whereIn('orders.id', $orderIds)
            ->where('orders.status', '!=', 'canceled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->first();
        
        if ($currentProductRevenue) {
            $revenueData['totalRevenue'] = $currentProductRevenue->total_revenue ?? 0;
            $orderCount = $currentProductRevenue->order_count ?? 0;
            $revenueData['averageOrderValue'] = $orderCount > 0 ? $revenueData['totalRevenue'] / $orderCount : 0;
            
            // Calculate previous period comparison (also product revenue only)
            $previousStartDate = (clone $startDate)->subDays($endDate->diffInDays($startDate));
            $previousEndDate = (clone $startDate)->subDay();
            
            $previousProductRevenue = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('order_items.product_id', $productIds)
                ->whereIn('orders.id', $orderIds)
                ->where('orders.status', '!=', 'canceled')
                ->whereBetween('orders.created_at', [$previousStartDate, $previousEndDate])
                ->select(DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'))
                ->first();
            
            $previousRevenue = $previousProductRevenue->total_revenue ?? 1; // Avoid division by zero
            $previousRevenue = $previousRevenue > 0 ? $previousRevenue : 1; // Safety check
            $revenueData['previousPeriodComparison'] = 
                (($revenueData['totalRevenue'] - $previousRevenue) / $previousRevenue) * 100;
        }

        // Prepare time series data based on selected period
        if ($period == 'daily') {
            // Daily sales data
            $salesData = $this->getDailySalesData($orderIds, $productIds, $startDate, $endDate);
        } elseif ($period == 'weekly') {
            // Weekly sales data
            $salesData = $this->getWeeklySalesData($orderIds, $productIds, $startDate, $endDate);
        } else {
            // Monthly sales data (default)
            $salesData = $this->getMonthlySalesData($orderIds, $productIds, $startDate, $endDate);
        }

        $salesOverviewLabels = $salesData['labels'];
        $salesOverviewData = $salesData['data'];
        $orderCountData = $salesData['orderCounts'];
        $weightData = $this->getWeightData($orderIds, $productIds, $startDate, $endDate);
        $weightOverviewLabels = $weightData['labels'];
        $weightOverviewData = $weightData['data'];

        // Get top selling products
        $topSellingProducts = $this->getTopSellingProducts($orderIds, $startDate, $endDate);

        // Get sales by category
        $salesByCategory = $this->getSalesByCategory($orderIds, $productIds, $startDate, $endDate);
        
        // Get customer locations data 
        $customerLocationData = $this->getCustomerLocationData($orderIds, $productIds, $startDate, $endDate);

        return view('seller.analytics', compact(
            'shop',
            'period',
            'dateRange',
            'startDate',
            'endDate',
            'salesOverviewData',
            'salesOverviewLabels',
            'orderCountData',
            'revenueData',
            'topSellingProducts',
            'salesByCategory',
            'customerLocationData',
            'weightOverviewLabels',
            'weightOverviewData',
        ));
    }

    /**
     * Get daily sales data (product revenue only)
     */
    private function getDailySalesData($orderIds, $productIds, $startDate, $endDate)
    {
        $results = [];
        $labels = [];
        $data = [];
        $orderCounts = [];

        // Query daily sales (product revenue only)
        $dailySales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('order_items.product_id', $productIds)
            ->whereIn('orders.id', $orderIds)
            ->where('orders.status', '!=', 'canceled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(orders.created_at) as date'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_sales'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Create a period generator for each day in range
        $period = new \DatePeriod(
            $startDate,
            new \DateInterval('P1D'),
            $endDate
        );

        // Initialize data array with zeros for all days
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $data[$dateStr] = 0;
            $orderCounts[$dateStr] = 0;
        }

        // Fill in actual data where available
        foreach ($dailySales as $sale) {
            $dateStr = Carbon::parse($sale->date)->format('Y-m-d');
            if (isset($data[$dateStr])) {
                $data[$dateStr] = (float)$sale->total_sales;
                $orderCounts[$dateStr] = (int)$sale->order_count;
            }
        }

        $results['labels'] = $labels;
        $results['data'] = array_values($data);
        $results['orderCounts'] = array_values($orderCounts);

        return $results;
    }

    /**
     * Get weekly sales data (product revenue only)
     */
    private function getWeeklySalesData($orderIds, $productIds, $startDate, $endDate)
    {
        $results = [];
        $labels = [];
        $data = [];
        $orderCounts = [];

        // Ensure start date is a Monday
        $startDate = (clone $startDate)->startOfWeek();
        
        // Query weekly sales (product revenue only)
        $weeklySales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('order_items.product_id', $productIds)
            ->whereIn('orders.id', $orderIds)
            ->where('orders.status', '!=', 'canceled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('YEARWEEK(orders.created_at, 1) as yearweek'),
                DB::raw('MIN(DATE(orders.created_at)) as week_start'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_sales'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('yearweek')
            ->orderBy('yearweek')
            ->get();
        
        // Create a period generator for each week
        $period = new \DatePeriod(
            $startDate,
            new \DateInterval('P1W'),
            $endDate
        );

        // Initialize data array with zeros for all weeks
        foreach ($period as $date) {
            $yearWeek = $date->format('YW');
            $weekStart = (clone $date)->startOfWeek()->format('d M');
            $weekEnd = (clone $date)->endOfWeek()->format('d M');
            $labels[] = $weekStart . ' - ' . $weekEnd;
            $data[$yearWeek] = 0;
            $orderCounts[$yearWeek] = 0;
        }

        // Fill in actual data where available
        foreach ($weeklySales as $sale) {
            $yearWeek = (string)$sale->yearweek;
            if (isset($data[$yearWeek])) {
                $data[$yearWeek] = (float)$sale->total_sales;
                $orderCounts[$yearWeek] = (int)$sale->order_count;
            }
        }

        $results['labels'] = $labels;
        $results['data'] = array_values($data);
        $results['orderCounts'] = array_values($orderCounts);

        return $results;
    }

    /**
     * Get monthly sales data (product revenue only)
     */
    private function getMonthlySalesData($orderIds, $productIds, $startDate, $endDate)
    {
        $results = [];
        $labels = [];
        $data = [];
        $orderCounts = [];

        // Ensure start date is first of month
        $startDate = (clone $startDate)->startOfMonth();
        
        // Query monthly sales (product revenue only)
        $monthlySales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('order_items.product_id', $productIds)
            ->whereIn('orders.id', $orderIds)
            ->where('orders.status', '!=', 'canceled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('YEAR(orders.created_at) as year'),
                DB::raw('MONTH(orders.created_at) as month'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_sales'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Create a period generator for each month
        $period = new \DatePeriod(
            $startDate,
            new \DateInterval('P1M'),
            $endDate
        );

        // Initialize data array with zeros for all months
        foreach ($period as $date) {
            $monthKey = $date->format('Y-m');
            $labels[] = $date->format('M Y');
            $data[$monthKey] = 0;
            $orderCounts[$monthKey] = 0;
        }

        // Fill in actual data where available
        foreach ($monthlySales as $sale) {
            $monthKey = sprintf('%d-%02d', $sale->year, $sale->month);
            if (isset($data[$monthKey])) {
                $data[$monthKey] = (float)$sale->total_sales;
                $orderCounts[$monthKey] = (int)$sale->order_count;
            }
        }

        $results['labels'] = $labels;
        $results['data'] = array_values($data);
        $results['orderCounts'] = array_values($orderCounts);

        return $results;
    }

    /**
     * Get top selling products
     */
    private function getTopSellingProducts($orderIds, $startDate, $endDate)
    {
        // First get the IDs of top selling products
        $topProductIds = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('order_items.order_id', $orderIds)
            ->where('orders.status', '!=', 'canceled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.id',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('products.id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->pluck('products.id');
        
        // Then use Eloquent to fetch the full product data with relationships
        $topProducts = Product::whereIn('id', $topProductIds)
            ->get();
        
        // Now enrich this data with the sales metrics
        foreach ($topProducts as $product) {
            $salesData = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('order_items.order_id', $orderIds)
                ->where('order_items.product_id', $product->id)
                ->where('orders.status', '!=', 'canceled')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('SUM(order_items.quantity) as total_quantity'),
                    DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
                )
                ->first();
            
            $product->total_quantity = $salesData ? $salesData->total_quantity : 0;
            $product->total_revenue = $salesData ? $salesData->total_revenue : 0;
        }
        
        // Sort by total_quantity to maintain the original order
        return $topProducts->sortByDesc('total_quantity')->values();
    }

    /**
     * Get sales by category
     */
    private function getSalesByCategory($orderIds, $productIds, $startDate, $endDate)
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('order_items.order_id', $orderIds)
            ->whereIn('products.id', $productIds)
            ->where('orders.status', '!=', 'canceled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();
    }

    /**
     * Get customer location data (product revenue only)
     */
    private function getCustomerLocationData($orderIds, $productIds, $startDate, $endDate)
    {
        return DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.id', $orderIds)
            ->whereIn('order_items.product_id', $productIds)
            ->where('orders.status', '!=', 'canceled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'orders.province_name',
                DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->whereNotNull('orders.province_name')
            ->groupBy('orders.province_name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();
    }

    private function getWeightData($orderIds, $productIds, $startDate, $endDate)
    {
        $results = [];
        $labels = [];
        $data = [];

        // Mengambil period dari parameter request bukan dari properti kelas
        $periodType = request('period', 'monthly'); // Default ke monthly
        
        if ($periodType == 'daily') {
            // Daily weight data
            $weightData = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('order_items.product_id', $productIds)
                ->whereIn('orders.id', $orderIds)
                ->where('orders.status', '!=', 'canceled')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('DATE(orders.created_at) as date'),
                    DB::raw('SUM(products.weight * order_items.quantity) as total_weight')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();
                
            // Create a period generator for each day in range
            $period = new \DatePeriod(
                $startDate,
                new \DateInterval('P1D'),
                $endDate
            );

            // Initialize data array with zeros for all days
            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
                $labels[] = $date->format('d M');
                $data[$dateStr] = 0;
            }

            // Fill in actual data where available
            foreach ($weightData as $weight) {
                $dateStr = Carbon::parse($weight->date)->format('Y-m-d');
                if (isset($data[$dateStr])) {
                    $data[$dateStr] = (float)$weight->total_weight;
                }
            }
        } elseif ($periodType == 'weekly') {
            // Weekly weight data
            $startDate = (clone $startDate)->startOfWeek();
            
            $weightData = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('order_items.product_id', $productIds)
                ->whereIn('orders.id', $orderIds)
                ->where('orders.status', '!=', 'canceled')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('YEARWEEK(orders.created_at, 1) as yearweek'),
                    DB::raw('MIN(DATE(orders.created_at)) as week_start'),
                    DB::raw('SUM(products.weight * order_items.quantity) as total_weight')
                )
                ->groupBy('yearweek')
                ->orderBy('yearweek')
                ->get();
                
            // Create a period generator for each week
            $period = new \DatePeriod(
                $startDate,
                new \DateInterval('P1W'),
                $endDate
            );

            // Initialize data array with zeros for all weeks
            foreach ($period as $date) {
                $yearWeek = $date->format('YW');
                $weekStart = (clone $date)->startOfWeek()->format('d M');
                $weekEnd = (clone $date)->endOfWeek()->format('d M');
                $labels[] = $weekStart . ' - ' . $weekEnd;
                $data[$yearWeek] = 0;
            }

            // Fill in actual data where available
            foreach ($weightData as $weight) {
                $yearWeek = (string)$weight->yearweek;
                if (isset($data[$yearWeek])) {
                    $data[$yearWeek] = (float)$weight->total_weight;
                }
            }
        } else {
            // Monthly weight data (default)
            $startDate = (clone $startDate)->startOfMonth();
            
            $weightData = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('order_items.product_id', $productIds)
                ->whereIn('orders.id', $orderIds)
                ->where('orders.status', '!=', 'canceled')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('YEAR(orders.created_at) as year'),
                    DB::raw('MONTH(orders.created_at) as month'),
                    DB::raw('SUM(products.weight * order_items.quantity) as total_weight')
                )
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
                
            // Create a period generator for each month
            $period = new \DatePeriod(
                $startDate,
                new \DateInterval('P1M'),
                $endDate
            );

            // Initialize data array with zeros for all months
            foreach ($period as $date) {
                $monthKey = $date->format('Y-m');
                $labels[] = $date->format('M Y');
                $data[$monthKey] = 0;
            }

            // Fill in actual data where available
            foreach ($weightData as $weight) {
                $monthKey = sprintf('%d-%02d', $weight->year, $weight->month);
                if (isset($data[$monthKey])) {
                    $data[$monthKey] = (float)$weight->total_weight;
                }
            }
        }

        $results['labels'] = $labels;
        $results['data'] = array_values($data);

        return $results;
    }
}