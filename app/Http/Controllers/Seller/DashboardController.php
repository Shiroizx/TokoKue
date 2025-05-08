<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the seller dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if the user has a shop
        $shop = Shop::where('user_id', $user->id)->first();
        
        // If the user doesn't have a shop yet, redirect to shop creation
        if (!$shop) {
            return redirect()->route('seller.shop.create')
                ->with('warning', 'Please create your shop to continue as a seller.');
        }

        // Inisialisasi variabel default
        $totalProducts = 0;
        $activeProducts = 0;
        $totalOrders = 0;
        $pendingOrders = 0;
        $recentOrders = collect([]);
        $lowStockProducts = collect([]);

        // Karena kita tahu bahwa products memiliki kolom seller_id (dari data sebelumnya)
        $totalProducts = Product::where('seller_id', $user->id)->count();
        $activeProducts = Product::where('seller_id', $user->id)
                        ->where('is_active', true)
                        ->count();
        
        // Dapatkan produk dengan stok rendah
        $lowStockProducts = Product::where('seller_id', $user->id)
                          ->where('stock', '<', 10)
                          ->where('is_active', true)
                          ->orderBy('stock', 'asc')
                          ->limit(5)
                          ->get();

        // Untuk orders, kita perlu cek strukturnya
        // Menggunakan relasi order items untuk menentukan order mana yang terkait dengan produk seller ini
        try {
            // Mencoba pendekatan dengan join ke order_items dan products
            // Dapatkan order IDs yang terkait dengan produk seller
            $orderIds = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.seller_id', $user->id)
                ->select('order_items.order_id')
                ->distinct()
                ->pluck('order_id');
            
            // Gunakan order IDs untuk mendapatkan data orders
            if (!empty($orderIds)) {
                $totalOrders = Order::whereIn('id', $orderIds)->count();
                $pendingOrders = Order::whereIn('id', $orderIds)
                               ->where('status', 'pending')
                               ->count();
                
                // Ambil recent orders
                $recentOrders = Order::whereIn('id', $orderIds)
                              ->with('user')
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get();
            }
        } catch (\Exception $e) {
            // Jika terjadi error, gunakan pendekatan alternatif
            // Coba cari orders dengan user_id yang sama (asumsi seller juga bisa menjadi buyer)
            $totalOrders = Order::where('user_id', $user->id)->count();
            $pendingOrders = Order::where('user_id', $user->id)
                           ->where('status', 'pending')
                           ->count();
            
            $recentOrders = Order::where('user_id', $user->id)
                         ->with('user')
                         ->orderBy('created_at', 'desc')
                         ->limit(5)
                         ->get();
        }

        // Get Sales Overview data (monthly sales for the last 12 months)
        $salesData = [];
        $salesLabels = [];
        
        try {
            // If we have orders, get monthly sales data
            if (!empty($orderIds)) {
                // Get monthly sales data for the last 12 months
                $monthlySales = DB::table('orders')
                    ->whereIn('id', $orderIds)
                    ->where('status', '!=', 'canceled')
                    ->where('created_at', '>=', Carbon::now()->subMonths(12))
                    ->select(
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('YEAR(created_at) as year'),
                        DB::raw('SUM(total_amount) as total_sales')
                    )
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();
                
                // Setup array for the past 12 months
                $last12Months = [];
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $monthKey = $date->format('Y-m');
                    $last12Months[$monthKey] = [
                        'month' => $date->format('M'),
                        'year' => $date->format('Y'),
                        'total' => 0
                    ];
                }
                
                // Fill in the data we have
                foreach ($monthlySales as $sale) {
                    $date = Carbon::createFromDate($sale->year, $sale->month, 1);
                    $monthKey = $date->format('Y-m');
                    if (isset($last12Months[$monthKey])) {
                        $last12Months[$monthKey]['total'] = (float) $sale->total_sales;
                    }
                }
                
                // Prepare data for charts
                foreach ($last12Months as $data) {
                    $salesLabels[] = $data['month'];
                    $salesData[] = $data['total'];
                }
            }
        } catch (\Exception $e) {
            // In case of error, use empty arrays
            $salesData = array_fill(0, 12, 0);
            $salesLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        }
        
        // Get Product Categories data
        $productCategories = [];
        
        try {
            // Get product counts by category for this seller
            $categoryCounts = DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('products.seller_id', $user->id)
                ->where('products.is_active', true)
                ->select('categories.name', DB::raw('COUNT(products.id) as count'))
                ->groupBy('categories.name')
                ->orderByDesc('count')
                ->limit(5)  // Top 5 categories
                ->get();
            
            foreach ($categoryCounts as $category) {
                $productCategories[] = [
                    'name' => $category->name,
                    'value' => $category->count
                ];
            }
            
            // If we have less than 5 categories, add "Other" for visual balance
            if (count($productCategories) < 5 && $totalProducts > array_sum(array_column($productCategories, 'value'))) {
                $otherCount = $totalProducts - array_sum(array_column($productCategories, 'value'));
                $productCategories[] = [
                    'name' => 'Other',
                    'value' => $otherCount
                ];
            }
        } catch (\Exception $e) {
            // In case of error, use placeholder data
            $productCategories = [
                ['name' => 'No Category Data', 'value' => 1]
            ];
        }

        return view('seller.dashboard', compact(
            'shop',
            'totalProducts',
            'activeProducts',
            'totalOrders',
            'pendingOrders',
            'recentOrders',
            'lowStockProducts',
            'salesData',
            'salesLabels',
            'productCategories'
        ));
    }
}