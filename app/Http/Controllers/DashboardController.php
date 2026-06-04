<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promo;
use App\Models\PartnerLocation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total stats
        $totalProducts = Product::count();
        $totalLocations = PartnerLocation::count();

        // Count only truly active promos (not draft, not expired)
        $activePromos = Promo::where('is_active', true)
            ->where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString())
            ->whereRaw('used_count < max_usage')
            ->count();

        // Product categories with count
        $productCategories = Product::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        // Recent activities (latest product created)
        $recentActivities = Product::latest()
            ->take(5)
            ->get()
            ->map(function ($product) {
                return [
                    'text' => 'Menambahkan produk baru "' . $product->name . '"',
                    'created_at' => $product->created_at
                ];
            });

        // Active promos with details
        $activePromosList = Promo::where('is_active', true)
            ->where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString())
            ->whereRaw('used_count < max_usage')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('admin.dashboard', [
            'totalProducts' => $totalProducts,
            'totalLocations' => $totalLocations,
            'activePromos' => $activePromos,
            'productCategories' => $productCategories,
            'recentActivities' => $recentActivities,
            'activePromosList' => $activePromosList,
        ]);
    }
}
