<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'orders' => Order::count(),
            'customers' => User::where('role', 'user')->count(),
            'revenue' => Order::where('status', Order::STATUS_COMPLETED)->sum('total'),
        ];

        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $bestSellers = Product::withSum('orderItems as sold_quantity', 'quantity')
            ->orderByDesc('sold_quantity')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'bestSellers'));
    }
}
