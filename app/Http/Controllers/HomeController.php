<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $heroBanner = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->first();

        $trending = Product::published()
            ->where('is_trending', true)
            ->with(['images', 'inventories'])
            ->take(8)
            ->get();

        $newArrivals = Product::published()
            ->where('is_new', true)
            ->with(['images', 'inventories'])
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        $sales = Product::published()
            ->where(function ($query) {
                $query->whereNotNull('sale_price')->orWhere('is_sale', true);
            })
            ->with(['images', 'inventories'])
            ->take(8)
            ->get();

        return view('home', [
            'heroBanner' => $heroBanner,
            'trending' => $trending,
            'newArrivals' => $newArrivals,
            'sales' => $sales,
        ]);
    }
}
