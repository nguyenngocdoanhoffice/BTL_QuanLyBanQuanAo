<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function index(): View
    {
        return view('cart.index', $this->cart->totals());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'size' => ['nullable', 'string', 'max:5'],
        ]);

        $product = Product::published()->findOrFail($data['product_id']);

        if ($data['size']) {
            $inventory = $product->inventories()->where('size', $data['size'])->first();

            if (! $inventory) {
                $inventory = $product->inventories()->whereNull('size')->first();
            }

            if ($inventory && $inventory->quantity <= 0) {
                return response()->json([
                    'message' => 'Size đã chọn hiện đã hết hàng.',
                ], 422);
            }
        } else {
            $inventory = $product->inventories()->whereNull('size')->first();

            if ($inventory && $inventory->quantity <= 0) {
                return response()->json([
                    'message' => 'Sản phẩm này hiện đã hết hàng.',
                ], 422);
            }
        }

        $item = $this->cart->add($product, $data['quantity'], $data['size']);

        return response()->json([
            'message' => 'Đã thêm sản phẩm vào giỏ hàng.',
            'item' => $item,
            'totals' => $this->cart->totals(),
        ]);
    }

    public function update(Request $request, string $key): JsonResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'size' => ['nullable', 'string', 'max:5'],
        ]);

        $currentItem = $this->cart->all()->get($key);

        if (! $currentItem) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm trong giỏ hàng.',
            ], 404);
        }

        $availableSizes = collect($currentItem['available_sizes'] ?? []);

        if ($data['size'] !== null && $availableSizes->isNotEmpty() && ! $availableSizes->contains($data['size'])) {
            return response()->json([
                'message' => 'Size đã chọn không có sẵn cho sản phẩm này.',
            ], 422);
        }

        $this->cart->update($key, $data['quantity'], $data['size'] ?? null);

        return response()->json([
            'message' => 'Đã cập nhật giỏ hàng.',
            'totals' => $this->cart->totals(),
        ]);
    }

    public function destroy(string $key): JsonResponse
    {
        $this->cart->remove($key);

        return response()->json([
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
            'totals' => $this->cart->totals(),
        ]);
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return redirect()->route('cart.index')->with('status', 'Đã xóa toàn bộ giỏ hàng.');
    }
}
