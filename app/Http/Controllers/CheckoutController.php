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
    private const FREE_SHIPPING_THRESHOLD = 1000000;
    private const SHIPPING_FEE = 39000;

    public function __construct(private readonly CartService $cart)
    {
        $this->middleware('auth');
    }

    public function index(): View|RedirectResponse
    {
        $totals = $this->cart->totals();

        if ($totals['count'] === 0) {
            return redirect()->route('cart.index')->with('status', 'Giỏ hàng của bạn đang trống.');
        }

        /** @var User $user */
        $user = Auth::user();

        $discountCode = session('checkout.discount_code');
        $checkout = $this->computeCheckoutTotals($totals, $discountCode);

        return view('checkout.index', [
            'cart' => $totals,
            'user' => $user,
            'discountCode' => $discountCode,
            'discountTotal' => $checkout['discount_total'],
            'shippingFee' => $checkout['shipping_fee'],
            'orderTotal' => $checkout['order_total'],
        ]);
    }

    public function applyDiscount(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'discount_code' => ['required', 'string', 'max:50'],
        ]);

        $code = Str::upper(trim($data['discount_code']));

        $discount = Discount::query()
            ->whereNull('product_id')
            ->where('code', $code)
            ->active()
            ->first();

        if (! $discount) {
            return back()->withErrors(['discount_code' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'])->withInput();
        }

        session(['checkout.discount_code' => $code]);

        return redirect()->route('checkout.index')->with('status', 'Đã áp dụng mã giảm giá.');
    }

    public function removeDiscount(): RedirectResponse
    {
        session()->forget('checkout.discount_code');

        return redirect()->route('checkout.index')->with('status', 'Đã gỡ mã giảm giá.');
    }

    public function store(Request $request): RedirectResponse
    {
        $totals = $this->cart->totals();

        if ($totals['count'] === 0) {
            return redirect()->route('cart.index')->with('status', 'Vui lòng thêm sản phẩm vào giỏ trước khi thanh toán.');
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
        $sessionCode = session('checkout.discount_code');
        $requestCode = $data['discount_code'] ? Str::upper(trim($data['discount_code'])) : null;
        $discountCode = $requestCode ?: $sessionCode;

        $checkout = $this->computeCheckoutTotals($totals, $discountCode);

        if ($discountCode && ! $checkout['discount']) {
            return back()->withErrors(['discount_code' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'])->withInput();
        }

        DB::transaction(function () use ($data, $user, $totals, $checkout) {
            $order = Order::create([
                'user_id' => $user->id,
                'code' => 'ORD-' . Str::upper(Str::random(6)),
                'status' => Order::STATUS_PREPARING,
                'payment_method' => $data['payment_method'],
                'payment_status' => $data['payment_method'] === 'online' ? 'paid' : 'pending',
                'subtotal' => $totals['subtotal'],
                'discount_total' => $checkout['discount_total'],
                'shipping_fee' => $checkout['shipping_fee'],
                'total' => $checkout['order_total'],
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

            if ($checkout['discount']) {
                $checkout['discount']->increment('used');
            }
        });

        session()->forget('checkout.discount_code');

        $this->cart->clear();

        return redirect()->route('profile.orders')->with('status', 'Đặt hàng thành công.');
    }

    private function computeCheckoutTotals(array $cart, ?string $discountCode): array
    {
        $shippingFee = $cart['subtotal'] >= self::FREE_SHIPPING_THRESHOLD ? 0 : self::SHIPPING_FEE;
        $discount = null;
        $discountTotal = 0;

        if ($discountCode) {
            $discount = Discount::query()
                ->whereNull('product_id')
                ->where('code', $discountCode)
                ->active()
                ->first();

            if ($discount) {
                $discountTotal = $discount->type === 'percentage'
                    ? round($cart['subtotal'] * ((float) $discount->value / 100))
                    : min((float) $discount->value, (float) $cart['subtotal']);
            }
        }

        return [
            'discount' => $discount,
            'discount_total' => $discountTotal,
            'shipping_fee' => $shippingFee,
            'order_total' => max((float) $cart['subtotal'] - $discountTotal + $shippingFee, 0),
        ];
    }
}
