<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
        $this->middleware('auth');
    }

    public function index(): View|RedirectResponse
    {
        $totals = $this->cart->totals();

        if ($totals['count'] === 0) {
            return redirect()->route('cart.index')->with('status', 'Your cart is empty.');
        }

        /** @var User $user */
        $user = Auth::user();

        return view('checkout.index', [
            'cart' => $totals,
            'user' => $user,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $totals = $this->cart->totals();

        if ($totals['count'] === 0) {
            return redirect()->route('cart.index')->with('status', 'Please add products before checking out.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'customer_email' => ['required', 'email'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['nullable', 'string', 'max:100'],
            'shipping_postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:cod,online'],
            'discount_code' => ['nullable', 'string', 'max:50'],
        ]);

        /** @var User $user */
        $user = Auth::user();
        $discount = null;
        $discountTotal = 0;

        if ($code = $data['discount_code'] ?? null) {
            $discount = Discount::where('code', $code)->active()->first();

            if (! $discount) {
                return back()->withErrors(['discount_code' => 'Invalid or expired discount code.']);
            }

            $discountTotal = $discount->type === 'percentage'
                ? ($totals['subtotal'] * ($discount->value / 100))
                : min($discount->value, $totals['subtotal']);
        }

        $shippingFee = $totals['subtotal'] >= 100 ? 0 : 3.99;

        DB::transaction(function () use ($data, $user, $totals, $discountTotal, $shippingFee, $discount) {
            $order = Order::create([
                'user_id' => $user->id,
                'code' => 'ORD-' . Str::upper(Str::random(6)),
                'status' => Order::STATUS_PREPARING,
                'payment_method' => $data['payment_method'],
                'payment_status' => $data['payment_method'] === 'online' ? 'paid' : 'pending',
                'subtotal' => $totals['subtotal'],
                'discount_total' => $discountTotal,
                'shipping_fee' => $shippingFee,
                'total' => max($totals['subtotal'] - $discountTotal + $shippingFee, 0),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'shipping_address' => $data['shipping_address'],
                'shipping_city' => $data['shipping_city'],
                'shipping_postal_code' => $data['shipping_postal_code'],
                'notes' => $data['notes'],
            ]);

            foreach ($totals['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'sku' => $item['sku'],
                    'size' => $item['size'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $inventoryQuery = Inventory::where('product_id', $item['product_id']);

                if ($item['size']) {
                    $inventoryQuery->where('size', $item['size']);
                } else {
                    $inventoryQuery->whereNull('size');
                }

                $inventory = $inventoryQuery->lockForUpdate()->first();

                if (! $inventory && $item['size']) {
                    $inventory = Inventory::where('product_id', $item['product_id'])
                        ->whereNull('size')
                        ->lockForUpdate()
                        ->first();
                }

                if ($inventory) {
                    $inventory->decrement('quantity', $item['quantity']);
                }
            }

            if ($discount) {
                $discount->increment('used');
            }
        });

        $this->cart->clear();

        return redirect()->route('profile.orders')->with('status', 'Order placed successfully.');
    }
}
