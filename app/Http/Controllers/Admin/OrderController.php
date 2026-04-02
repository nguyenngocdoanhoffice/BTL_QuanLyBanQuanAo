<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $statuses = Order::statusOptions();
        $statusFilter = $request->string('status')->toString();

        $orders = Order::query()
            ->when($statusFilter, function ($query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => $statuses,
            'activeStatus' => $statusFilter,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['items.product', 'user']);

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => Order::statusOptions(),
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::statusOptions()))],
        ]);

        $order->update($validated);

        return back()->with('status', 'Cập nhật trạng thái đơn hàng thành công.');
    }
}
