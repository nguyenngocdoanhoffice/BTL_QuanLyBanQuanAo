<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();
        $recentOrders = $user?->orders()->latest()->take(5)->get() ?? collect();
        $ordersCount = $user?->orders()->count() ?? 0;
        $lifetimeSpend = $user?->orders()->sum('total') ?? 0;

        return view('dashboard.user', [
            'user' => $user,
            'recentOrders' => $recentOrders,
            'ordersCount' => $ordersCount,
            'lifetimeSpend' => $lifetimeSpend,
        ]);
    }
}
